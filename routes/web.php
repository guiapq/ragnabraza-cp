<?php

use App\Http\Controllers\Game\AccountOverviewController;
use App\Http\Controllers\Game\CharacterController;
use App\Http\Controllers\Info\PlayersOnlineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (\App\Services\WorldDataService $worldData) {
    $seed = $worldData->getActiveSeed();
    $serverStatus = $worldData->getServerStatus();
    $generalStats = $worldData->getGeneralStats();

    $news = [
        [
            'id' => 1,
            'title' => 'Temporada Roguelike Ativa: Loop SSF de 12 Horas',
            'category' => 'Temporada',
            'badge_color' => 'text-bg-warning',
            'author' => 'luiz (Dev)',
            'time' => 'Hoje',
            'excerpt' => 'Mundo gerado deterministicamente pela seed ativa. Progressão solo equilibrada com consumíveis e minérios em escala por tier.',
            'content' => 'O loop foi planejado para viabilizar avanço completo Solo Self-Found (SSF) sem necessidade de grind punitivo de semanas. Monstros fornecem consumíveis, flechas, pedras e equipamentos escalonados pelo nível do mapa.'
        ],
        [
            'id' => 2,
            'title' => 'Lojas SOTN Rebalanceadas & Pre-Renewal Canônico',
            'category' => 'Mundo',
            'badge_color' => 'text-bg-info',
            'author' => 'luiz (Dev)',
            'time' => 'Atualizado',
            'excerpt' => 'Lojas distribuídas por temática inspiradas em Castlevania SOTN: armas, armaduras, consumíveis e reagentes com preços originais.',
            'content' => 'Armas iniciais para todas as classes em lojas dedicadas, consumíveis em farmácias e reagentes em cacarecos. Itens de quest ou bugados foram totalmente expurgados da geração.'
        ],
        [
            'id' => 3,
            'title' => 'Cliente WebAssembly Integrado com Nomes PT-BR',
            'category' => 'Cliente',
            'badge_color' => 'text-bg-success',
            'author' => 'luiz (Dev)',
            'time' => 'Recente',
            'excerpt' => 'Conecte diretamente pelo navegador via roBrowser com liblua5.1.wasm e decodificação oficial de itens em português.',
            'content' => 'Sem instalação no sistema operacional: abra o navegador e jogue imediatamente com suporte nativo a som, efeitos visuais e resolução ajustável.'
        ]
    ];

    $changelogs = [
        [
            'badge' => 'v2.0 Estável',
            'badge_class' => 'text-bg-success',
            'title' => 'Lançamento da v2.0 Estável com pipeline procedural e cliente WebAssembly',
            'time' => 'Recente'
        ],
        [
            'badge' => 'Segurança',
            'badge_class' => 'text-bg-warning',
            'title' => 'Correção de parsing de scripts de itens, YAML imports e exploits de lojas',
            'time' => 'Ontem'
        ],
        [
            'badge' => 'Otimização',
            'badge_class' => 'text-bg-info',
            'title' => 'Compilação paralela com nproc e Docker Registry local na porta 5000',
            'time' => 'Esta semana'
        ],
        [
            'badge' => 'Localização',
            'badge_class' => 'text-bg-primary',
            'title' => 'Integração de nomes PT-BR de itens e decodificação de strings no roBrowser',
            'time' => 'Esta semana'
        ]
    ];

    // Amostra de mercado (vending de jogadores e itens populares)
    $playerVendings = $worldData->getPlayerVendings();
    $items = $worldData->getItems();
    $marketSample = [];

    if (!empty($playerVendings)) {
        $marketSample = array_slice($playerVendings, 0, 4);
    } else {
        $sampleIds = [501, 601, 713, 1101];
        foreach ($sampleIds as $sId) {
            if (isset($items[$sId])) {
                $marketSample[] = [
                    'item_id' => $sId,
                    'item_name' => $items[$sId]['name'],
                    'merchant_name' => 'NPC Trader',
                    'shop_title' => 'Loja da Cidade',
                    'map' => 'prontera',
                    'amount' => 10,
                    'price' => $items[$sId]['buy'],
                    'refine' => 0,
                    'sprite_url' => $items[$sId]['sprite_url'],
                ];
            }
        }
    }

    try {
        $speedruns = \App\Models\Game\EventSpeedrun::eligible()->orderBy('total_seconds', 'asc')->take(5)->get();
    } catch (\Exception $e) {
        $speedruns = collect();
    }

    try {
        $mvpBounties = \App\Models\Game\EventMvpKill::eligible()->select(
            'char_id',
            'char_name',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_kills'),
            \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT mob_id) as distinct_mvps')
        )
        ->groupBy('char_id', 'char_name')
        ->orderByDesc('total_kills')
        ->take(5)
        ->get();
    } catch (\Exception $e) {
        $mvpBounties = collect();
    }

    return view('welcome', compact(
        'seed',
        'serverStatus',
        'generalStats',
        'news',
        'changelogs',
        'marketSample',
        'speedruns',
        'mvpBounties'
    ));
})->name('home');

Route::get('/market', [\App\Http\Controllers\Game\MarketController::class, 'index'])->name('market.index');
Route::get('/market/item/{id}', [\App\Http\Controllers\Game\MarketController::class, 'itemDetails'])->name('market.item');

// Enciclopédia de Monstros
Route::get('/mobdb', [\App\Http\Controllers\Game\MobDbController::class, 'index'])->name('mobdb.index');
Route::get('/mobdb/{id}', [\App\Http\Controllers\Game\MobDbController::class, 'show'])->where('id', '[0-9]+')->name('mobdb.show');


Route::get('/scoreboard', function () {
    $seed = 'default';
    $randoFile = base_path('../.env.rando');
    if (file_exists($randoFile)) {
        $content = file_get_contents($randoFile);
        if (preg_match('/^WORLD_SEED=(.*)$/m', $content, $matches)) {
            $seed = trim($matches[1]);
        }
    }

    try {
        $speedruns = \App\Models\Game\EventSpeedrun::eligible()->orderBy('total_seconds', 'asc')->take(10)->get();
    } catch (\Exception $e) {
        $speedruns = collect();
    }

    try {
        $mvpBounties = \App\Models\Game\EventMvpKill::eligible()->select(
            'char_id',
            'char_name',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_kills'),
            \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT mob_id) as distinct_mvps')
        )
        ->groupBy('char_id', 'char_name')
        ->orderByDesc('total_kills')
        ->take(10)
        ->get();
    } catch (\Exception $e) {
        $mvpBounties = collect();
    }

    try {
        $recentKills = \App\Models\Game\EventMvpKill::eligible()->orderByDesc('killed_at')->take(6)->get();
    } catch (\Exception $e) {
        $recentKills = collect();
    }

    return view('scoreboard', compact('seed', 'speedruns', 'mvpBounties', 'recentKills'));
})->name('event.scoreboard');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('player')
    ->group(function () {
        // Base Endpoints
        Route::view('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/overview', [AccountOverviewController::class, 'viewAccountOverview'])
            ->name('game.overview');

        Route::get('/online', [PlayersOnlineController::class, 'viewPlayersOnline'])
            ->name('game.online-players');

        // Character
        Route::prefix('/character')->group(function () {
            Route::get('{characterId}/settings', [CharacterController::class, 'viewCharacterSettings'])
                ->name('game.character.settings');
            Route::post('{character}/settings', [CharacterController::class, 'postCharacterSettings'])
                ->name('game.character.settings-update');
            Route::post('{characterId}/unstuck', [CharacterController::class, 'unstuck'])
                ->name('game.character.unstuck');
        });
    });
