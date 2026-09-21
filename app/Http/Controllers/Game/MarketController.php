<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\WorldDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketController extends Controller
{
    public function __construct(
        protected WorldDataService $worldData
    ) {}

    /**
     * Página principal de Busca de Mercado & Itens (FluxCP + Divine Pride).
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('q', ''));
        $tab = $request->input('tab', 'all'); // 'all', 'players', 'npc', 'drops'

        $items = $this->worldData->getItems();
        $playerVendings = $this->worldData->getPlayerVendings($search);
        $npcShopsData = $this->worldData->getNpcShops();
        $itemToNpcShops = $npcShopsData['item_to_shops'] ?? [];
        $itemToMobs = $this->worldData->getItemToMobs();

        // Filtragem de itens para o catálogo
        $searchResults = [];
        if (!empty($search)) {
            $s = strtolower($search);
            foreach ($items as $id => $item) {
                if (
                    str_contains((string)$id, $s) ||
                    str_contains(strtolower($item['name']), $s) ||
                    str_contains(strtolower($item['aegis']), $s)
                ) {
                    $itemDetail = $item;
                    $itemDetail['player_vendings'] = array_values(array_filter($playerVendings, fn($v) => $v['item_id'] === $id));
                    $itemDetail['npc_shops'] = $itemToNpcShops[$id] ?? [];
                    $itemDetail['dropped_by'] = $itemToMobs[$id] ?? [];

                    $searchResults[] = $itemDetail;

                    if (count($searchResults) >= 40) {
                        break;
                    }
                }
            }
        } else {
            // Itens em destaque / comuns quando sem busca
            $popularIds = [501, 502, 503, 504, 601, 602, 603, 611, 713, 909, 1002, 1101, 1201, 1701, 2301, 2401, 2501];
            foreach ($popularIds as $id) {
                if (isset($items[$id])) {
                    $itemDetail = $items[$id];
                    $itemDetail['player_vendings'] = array_values(array_filter($playerVendings, fn($v) => $v['item_id'] === $id));
                    $itemDetail['npc_shops'] = $itemToNpcShops[$id] ?? [];
                    $itemDetail['dropped_by'] = $itemToMobs[$id] ?? [];
                    $searchResults[] = $itemDetail;
                }
            }
        }

        $activeSeed = $this->worldData->getActiveSeed();

        return view('market', [
            'search' => $search,
            'tab' => $tab,
            'results' => $searchResults,
            'playerVendingsCount' => count($playerVendings),
            'totalItemsCount' => count($items),
            'activeSeed' => $activeSeed,
        ]);
    }

    /**
     * Endpoint JSON para detalhes de um item específico (para modais/autocomplete).
     */
    public function itemDetails(int $id): JsonResponse
    {
        $items = $this->worldData->getItems();
        if (!isset($items[$id])) {
            return response()->json(['error' => 'Item não encontrado'], 404);
        }

        $item = $items[$id];
        $playerVendings = $this->worldData->getPlayerVendings((string)$id);
        $npcShopsData = $this->worldData->getNpcShops();
        $itemToMobs = $this->worldData->getItemToMobs();

        $item['player_vendings'] = array_values(array_filter($playerVendings, fn($v) => $v['item_id'] === $id));
        $item['npc_shops'] = $npcShopsData['item_to_shops'][$id] ?? [];
        $item['dropped_by'] = $itemToMobs[$id] ?? [];

        return response()->json($item);
    }
}
