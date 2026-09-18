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

Route::get('/', function () {
    return view('welcome');
});

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
        $speedruns = \App\Models\Game\EventSpeedrun::orderBy('total_seconds', 'asc')->take(10)->get();
    } catch (\Exception $e) {
        $speedruns = collect();
    }

    try {
        $mvpBounties = \App\Models\Game\EventMvpKill::select(
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
        $recentKills = \App\Models\Game\EventMvpKill::orderByDesc('killed_at')->take(6)->get();
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
        });
    });
