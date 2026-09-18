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

    public static array $RACES = [
        0 => 'Formless',
        1 => 'Undead',
        2 => 'Brute',
        3 => 'Plant',
        4 => 'Insect',
        5 => 'Fish',
        6 => 'Demon',
        7 => 'Demi-Human',
        8 => 'Angel',
        9 => 'Dragon',
        10 => 'Player',
        11 => 'Boss',
        12 => 'Non-Boss',
    ];

    public static array $ELEMENTS = [
        0 => 'Neutral',
        1 => 'Water',
        2 => 'Earth',
        3 => 'Fire',
        4 => 'Wind',
        5 => 'Poison',
        6 => 'Holy',
        7 => 'Shadow',
        8 => 'Ghost',
        9 => 'Undead',
    ];

    public static function formatElement(int $element): string
    {
        $level = max(1, intdiv($element, 20));
        $type = $element % 20;
        $name = self::$ELEMENTS[$type] ?? "Elem {$type}";
        return "{$name} {$level}";
    }

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
            if (count($cols) < 25) {
                continue;
            }

            $id = $cols[0];
            $name = $cols[3] !== '' ? $cols[3] : $cols[2];
            $lv = $cols[4];
            $hp = $cols[5];
            $atk1 = $cols[10] ?? '0';
            $atk2 = $cols[11] ?? '0';
            $def = $cols[12] ?? '0';
            $mdef = $cols[13] ?? '0';
            $race = (int)($cols[23] ?? 0);
            $element = (int)($cols[24] ?? 0);

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
                'atk' => "{$atk1}~{$atk2}",
                'def' => $def,
                'mdef' => $mdef,
                'race' => self::$RACES[$race] ?? "Race {$race}",
                'element' => self::formatElement($element),
            ];

            if (count($mobs) >= 50) {
                break;
            }
        }

        return collect($mobs);
    }
}
