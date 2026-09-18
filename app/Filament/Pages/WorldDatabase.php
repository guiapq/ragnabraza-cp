<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Collection;

class WorldDatabase extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'World Generation';

    protected static ?string $title = 'Procedural World Explorer';

    protected static string $view = 'filament.pages.world-database';

    public string $search = '';
    public string $selectedCategory = 'mobs';

    public function getActiveSeed(): string
    {
        $randoFile = base_path('../.env.rando');
        if (file_exists($randoFile)) {
            $content = file_get_contents($randoFile);
            if (preg_match('/^WORLD_SEED=(.*)$/m', $content, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'default';
    }

    public function getMobs(): Collection
    {
        $mobFile = base_path('game-data/db/re/mob_db.txt');
        if (!file_exists($mobFile)) {
            $mobFile = base_path('../data/db/re/mob_db.txt');
        }

        if (!file_exists($mobFile)) {
            return collect();
        }

        $mobs = [];
        $lines = file($mobFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with($line, '//') || empty(trim($line))) {
                continue;
            }

            $cols = explode(',', $line);
            if (count($cols) < 23) {
                continue;
            }

            $id = $cols[0];
            $name = $cols[2];
            $lv = $cols[3];
            $hp = $cols[4];
            $def = $cols[11];
            $mdef = $cols[12];
            $race = $cols[21];
            $element = $cols[22];

            if ($this->search !== '') {
                $s = strtolower($this->search);
                if (!str_contains(strtolower($name), $s) && !str_contains($id, $s)) {
                    continue;
                }
            }

            $mobs[] = [
                'id' => $id,
                'name' => $name,
                'level' => $lv,
                'hp' => number_format((int)$hp),
                'def' => $def,
                'mdef' => $mdef,
                'race' => $race,
                'element' => $element,
            ];

            if (count($mobs) >= 50) {
                break;
            }
        }

        return collect($mobs);
    }
}
