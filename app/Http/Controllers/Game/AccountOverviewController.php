<?php

namespace App\Http\Controllers\Game;

use App\Data\CharacterSettings;
use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use Illuminate\View\View;

class AccountOverviewController extends Controller
{
    public function viewAccountOverview(): View
    {
        $seed = 'default';
        $randoFile = base_path('../.env.rando');
        if (file_exists($randoFile)) {
            $content = file_get_contents($randoFile);
            if (preg_match('/^WORLD_SEED=(.*)$/m', $content, $matches)) {
                $seed = trim($matches[1]);
            }
        }

        return view('game.overview', compact('seed'));
    }

}
