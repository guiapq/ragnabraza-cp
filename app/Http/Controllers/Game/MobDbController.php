<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\WorldDataService;
use Illuminate\Http\Request;

class MobDbController extends Controller
{
    public function __construct(protected WorldDataService $worldData) {}

    /**
     * Listagem de monstros com busca e filtros.
     */
    public function index(Request $request)
    {
        $search  = trim($request->get('q', ''));
        $race    = $request->get('race', '');
        $element = $request->get('element', '');
        $sort    = $request->get('sort', 'id');
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = 48;

        $mobs = $this->worldData->getMobs();

        // Filtros
        if ($search !== '') {
            $lower = mb_strtolower($search);
            $mobs = array_filter($mobs, function ($m) use ($lower) {
                return str_contains(mb_strtolower($m['name']), $lower)
                    || (string)$m['id'] === $lower;
            });
        }

        if ($race !== '') {
            $mobs = array_filter($mobs, fn($m) => $m['race'] === $race);
        }

        if ($element !== '') {
            $mobs = array_filter($mobs, fn($m) => str_starts_with($m['element'], $element));
        }

        // Ordenação
        usort($mobs, match($sort) {
            'name'  => fn($a, $b) => strcmp($a['name'], $b['name']),
            'level' => fn($a, $b) => $a['level'] <=> $b['level'],
            'hp'    => fn($a, $b) => $a['hp'] <=> $b['hp'],
            'exp'   => fn($a, $b) => $b['exp'] <=> $a['exp'],
            default => fn($a, $b) => $a['id'] <=> $b['id'],
        });

        $total    = count($mobs);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page     = min($page, $totalPages);
        $mobs     = array_slice($mobs, ($page - 1) * $perPage, $perPage);

        return view('mobdb.index', [
            'mobs'        => $mobs,
            'search'      => $search,
            'race'        => $race,
            'element'     => $element,
            'sort'        => $sort,
            'total'       => $total,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'perPage'     => $perPage,
            'races'       => WorldDataService::RACES,
            'elements'    => WorldDataService::ELEMENTS,
            'activeSeed'  => $this->worldData->getActiveSeed(),
        ]);
    }

    /**
     * Página de detalhe de um monstro.
     */
    public function show(Request $request, int $id)
    {
        $mobs = $this->worldData->getMobs();

        if (!isset($mobs[$id])) {
            abort(404, "Monstro #{$id} não encontrado nesta seed.");
        }

        $mob    = $mobs[$id];
        $spawns = $this->worldData->getMobSpawns();
        $maps   = $spawns['mob_to_maps'][$id] ?? ($spawns[$id] ?? []);
        $mapCounts = [];
        foreach ($maps as $mapName) {
            $mapCounts[$mapName] = $spawns['map_to_mobs'][$mapName][$id] ?? 1;
        }

        // Ordenar drops: maior rate primeiro
        usort($mob['drops'], fn($a, $b) => $b['rate_percent'] <=> $a['rate_percent']);

        return view('mobdb.show', [
            'mob'        => $mob,
            'maps'       => $maps,
            'mapCounts'  => $mapCounts,
            'activeSeed' => $this->worldData->getActiveSeed(),
        ]);
    }
}
