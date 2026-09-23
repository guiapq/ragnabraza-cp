<?php

namespace App\Services;

use App\Models\Game\Character;
use App\Models\Game\EventMvpKill;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WorldDataService
{
    public const RACES = [
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

    public const ELEMENTS = [
        0 => 'Neutro',
        1 => 'Água',
        2 => 'Terra',
        3 => 'Fogo',
        4 => 'Vento',
        5 => 'Veneno',
        6 => 'Sagrado',
        7 => 'Sombrio',
        8 => 'Fantasma',
        9 => 'Maldito',
    ];

    public const ITEM_TYPES = [
        0 => 'Cura',
        2 => 'Usável',
        3 => 'Diversos',
        4 => 'Arma',
        5 => 'Armadura',
        6 => 'Carta',
        7 => 'Ovo de Pet',
        8 => 'Equip. Pet',
        10 => 'Munição',
        11 => 'Usável com Delay',
        18 => 'Consumível Cash',
    ];

    /**
     * Retorna a seed ativa do mundo procedural (.env.rando ou zawarudo).
     */
    public function getActiveSeed(): string
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('world_metadata')) {
                $dbSeed = DB::table('world_metadata')->where('key', 'active_seed')->value('value');
                if ($dbSeed) {
                    return trim($dbSeed);
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }

        $paths = [
            base_path('../.env.rando'),
            base_path('.env.rando'),
            dirname(base_path()) . '/.env.rando',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $content = file_get_contents($path);
                if (preg_match('/^WORLD_SEED=(.*)$/m', $content, $matches)) {
                    return trim($matches[1]);
                }
            }
        }

        return env('WORLD_SEED', 'zawarudo');
    }

    /**
     * Resolve o caminho dos dados de runtime ou data_base.
     */
    public function getDataPath(string $subpath = ''): ?string
    {
        $candidates = [
            base_path('game-data/' . ltrim($subpath, '/')),
            base_path('../data/' . ltrim($subpath, '/')),
            '/var/www/html/game-data/' . ltrim($subpath, '/'),
            base_path('../data_base/' . ltrim($subpath, '/')),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) || is_dir($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Mapa de traduções em português para os itens.
     */
    public function getPtBrTranslations(): array
    {
        return Cache::remember('valid_ptbr_items_map', 3600, function () {
            $paths = [
                storage_path('app/game-data/valid_ptbr_items.json'),
                base_path('../tools/valid_ptbr_items.json'),
                base_path('game-data/valid_ptbr_items.json'),
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $json = json_decode(file_get_contents($path), true);
                    if (is_array($json)) {
                        return $json;
                    }
                }
            }
            return [];
        });
    }

    /**
     * Carrega e processa a tabela de itens diretamente da base SQL (item_db).
     */
    public function getItems(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_items_{$seed}", 600, function () {
            $ptBr = $this->getPtBrTranslations();
            $items = [];

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('item_db')) {
                    $rows = DB::table('item_db')->get();
                    foreach ($rows as $row) {
                        $id = (int)$row->id;
                        $origName = $row->name_japanese ?: $row->name_english;
                        $name = $ptBr[$id] ?? $origName;
                        $typeCode = (int)$row->type;

                        $items[$id] = [
                            'id' => $id,
                            'aegis' => $row->name_english,
                            'name' => $name,
                            'original_name' => $origName,
                            'type_id' => $typeCode,
                            'type' => self::ITEM_TYPES[$typeCode] ?? 'Outros',
                            'buy' => (int)($row->price_buy ?? 0),
                            'sell' => (int)($row->price_sell ?? 0),
                            'weight' => ((int)$row->weight) / 10,
                            'atk' => (int)($row->attack ?? 0),
                            'def' => (int)($row->defence ?? 0),
                            'slots' => (int)($row->slots ?? 0),
                            'script' => $row->script ?? '',
                            'sprite_url' => "https://static.divine-pride.net/images/items/item/{$id}.png",
                            'divine_url' => "https://www.divine-pride.net/database/item/{$id}",
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // silenciar falhas de conexão temporárias
            }

            return $items;
        });
    }

    /**
     * Carrega e processa a tabela de monstros e seus drops diretamente da base SQL (mob_db).
     */
    public function getMobs(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_mobs_{$seed}", 600, function () {
            $items = $this->getItems();
            $mobs = [];

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('mob_db')) {
                    $rows = DB::table('mob_db')->get();
                    foreach ($rows as $row) {
                        $id = (int)$row->ID;
                        $name = trim(!empty($row->iName) ? $row->iName : $row->kName);
                        $elemCode = (int)$row->Element;
                        $elemType = $elemCode % 20;
                        $elemLv = max(1, intdiv($elemCode, 20));
                        $elemName = (self::ELEMENTS[$elemType] ?? "Elem {$elemType}") . " {$elemLv}";

                        $drops = [];
                        for ($i = 1; $i <= 9; $i++) {
                            $dropId = (int)($row->{"Drop{$i}id"} ?? 0);
                            $dropRate = (int)($row->{"Drop{$i}per"} ?? 0);
                            if ($dropId > 0 && $dropRate > 0) {
                                $drops[] = [
                                    'item_id' => $dropId,
                                    'item_name' => $items[$dropId]['name'] ?? "Item #{$dropId}",
                                    'rate_raw' => $dropRate,
                                    'rate_percent' => max(0.01, round($dropRate / 100, 2)),
                                    'sprite_url' => "https://static.divine-pride.net/images/items/item/{$dropId}.png",
                                ];
                            }
                        }
                        $cardId = (int)($row->DropCardid ?? 0);
                        $cardRate = (int)($row->DropCardper ?? 0);
                        if ($cardId > 0 && $cardRate > 0) {
                            $drops[] = [
                                'item_id' => $cardId,
                                'item_name' => $items[$cardId]['name'] ?? "Item #{$cardId}",
                                'rate_raw' => $cardRate,
                                'rate_percent' => max(0.01, round($cardRate / 100, 2)),
                                'sprite_url' => "https://static.divine-pride.net/images/items/item/{$cardId}.png",
                                'is_card' => true,
                            ];
                        }
                        for ($i = 1; $i <= 3; $i++) {
                            $mvpId = (int)($row->{"MVP{$i}id"} ?? 0);
                            $mvpRate = (int)($row->{"MVP{$i}per"} ?? 0);
                            if ($mvpId > 0 && $mvpRate > 0) {
                                $drops[] = [
                                    'item_id' => $mvpId,
                                    'item_name' => $items[$mvpId]['name'] ?? "Item #{$mvpId}",
                                    'rate_raw' => $mvpRate,
                                    'rate_percent' => max(0.01, round($mvpRate / 100, 2)),
                                    'sprite_url' => "https://static.divine-pride.net/images/items/item/{$mvpId}.png",
                                    'is_mvp' => true,
                                ];
                            }
                        }

                            $mode = (int)($row->Mode ?? 0);

                            $mobs[$id] = [
                                'id' => $id,
                                'name' => $name,
                                'level' => (int)$row->LV,
                                'hp' => (int)$row->HP,
                                'exp' => (int)$row->EXP,
                                'jexp' => (int)$row->JEXP,
                                'atk' => "{$row->ATK1}~{$row->ATK2}",
                                'def' => (int)$row->DEF,
                                'mdef' => (int)$row->MDEF,
                                'race_id' => (int)$row->Race,
                                'race' => self::RACES[(int)$row->Race] ?? "Race {$row->Race}",
                                'element_code' => $elemCode,
                                'element' => $elemName,
                                'mode' => $mode,
                                'mode_hex' => '0x' . strtoupper(dechex($mode)),
                                'behaviors' => self::decodeMobMode($mode),
                                'is_aggressive' => (bool)(($mode & 0x0080) || ($mode & 0x0004)),
                                'is_boss' => (bool)($mode & 0x0020),
                                'drops' => $drops,
                                'icon_url' => "https://static.divine-pride.net/images/mobs/png/{$id}.png",
                                'sprite_url' => "https://static.divine-pride.net/images/mobs/{$id}.gif",
                                'anim_url' => "https://static.divine-pride.net/images/mobs/{$id}.gif",
                                'divine_url' => "https://www.divine-pride.net/database/monster/{$id}",
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                // silenciar falhas de conexão temporárias
            }

            return $mobs;
        });
    }

    /**
     * Decodifica a bitmask Mode do rAthena em comportamentos legíveis.
     */
    public static function decodeMobMode(int $mode): array
    {
        $behaviors = [];

        // Agressividade básica
        if ($mode & 0x0080 || $mode & 0x0004) {
            $behaviors[] = ['key' => 'aggressive', 'label' => 'Agressivo', 'type' => 'danger'];
        } else {
            $behaviors[] = ['key' => 'passive', 'label' => 'Passivo', 'type' => 'success'];
        }

        if ($mode & 0x0001) {
            $behaviors[] = ['key' => 'can_move', 'label' => 'Móvel', 'type' => 'secondary'];
        }
        if ($mode & 0x0002) {
            $behaviors[] = ['key' => 'looter', 'label' => 'Coleta Itens (Looter)', 'type' => 'info'];
        }
        if ($mode & 0x0008) {
            $behaviors[] = ['key' => 'assist', 'label' => 'Ajuda Aliados (Social)', 'type' => 'primary'];
        }
        if ($mode & 0x0010) {
            $behaviors[] = ['key' => 'cast_sensor', 'label' => 'Reage a Magias (Cast Sensor)', 'type' => 'warning'];
        }
        if ($mode & 0x0020) {
            $behaviors[] = ['key' => 'boss', 'label' => 'Chefe / MVP', 'type' => 'danger'];
        }
        if ($mode & 0x0040) {
            $behaviors[] = ['key' => 'plant', 'label' => 'Planta (Imóvel)', 'type' => 'secondary'];
        }
        if ($mode & 0x0100) {
            $behaviors[] = ['key' => 'change_target_chase', 'label' => 'Muda Alvo na Perseguição', 'type' => 'dark'];
        }
        if ($mode & 0x0800) {
            $behaviors[] = ['key' => 'detect_hide', 'label' => 'Detecta Esconderijo', 'type' => 'dark'];
        }
        if ($mode & 0x1000) {
            $behaviors[] = ['key' => 'detect_cloak', 'label' => 'Detecta Furtividade (Cloak)', 'type' => 'dark'];
        }

        return $behaviors;
    }

    /**
     * Mapeamento reverso: dado um item_id, retorna todos os monstros que o dropam.
     */
    public function getItemToMobs(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_item_to_mobs_{$seed}", 600, function () {
            $mobs = $this->getMobs();
            $index = [];

            foreach ($mobs as $mob) {
                foreach ($mob['drops'] as $drop) {
                    $itemId = $drop['item_id'];
                    if (!isset($index[$itemId])) {
                        $index[$itemId] = [];
                    }

                    $index[$itemId][] = [
                        'mob_id' => $mob['id'],
                        'mob_name' => $mob['name'],
                        'mob_level' => $mob['level'],
                        'rate_percent' => $drop['rate_percent'],
                        'icon_url' => $mob['icon_url'] ?? "https://static.divine-pride.net/images/mobs/png/{$mob['id']}.png",
                        'sprite_url' => $mob['sprite_url'],
                    ];
                }
            }

            // Ordena os drops de cada item pela maior chance
            foreach ($index as &$mobList) {
                usort($mobList, fn($a, $b) => $b['rate_percent'] <=> $a['rate_percent']);
            }

            return $index;
        });
    }

    /**
     * Carrega spawns de monstros nos mapas.
     */
    public function getMobSpawns(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_mob_spawns_{$seed}", 600, function () {
            $mobToMaps = [];
            $mapToMobs = [];

            $spawnDirs = [
                $this->getDataPath('npc/pre-re/mobs'),
                $this->getDataPath('npc/re/mobs'),
                $this->getDataPath('npc/mobs'),
            ];

            foreach ($spawnDirs as $dir) {
                if (!$dir || !is_dir($dir)) {
                    continue;
                }

                $files = File::allFiles($dir);
                foreach ($files as $file) {
                    $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        if (str_starts_with($line, '//') || !str_contains($line, 'monster')) {
                            continue;
                        }

                        $parts = explode("\t", $line);
                        if (count($parts) < 4) {
                            continue;
                        }

                        $locParts = explode(',', $parts[0]);
                        $mapName = trim($locParts[0] ?? '');
                        if (empty($mapName)) {
                            continue;
                        }

                        $mobData = explode(',', $parts[3]);
                        $mobId = (int)trim($mobData[0] ?? 0);
                        $count = (int)trim($mobData[1] ?? 1);

                        if ($mobId <= 0) {
                            continue;
                        }

                        // mob -> maps
                        if (!isset($mobToMaps[$mobId])) {
                            $mobToMaps[$mobId] = [];
                        }
                        if (!in_array($mapName, $mobToMaps[$mobId])) {
                            $mobToMaps[$mobId][] = $mapName;
                        }

                        // map -> mobs
                        if (!isset($mapToMobs[$mapName])) {
                            $mapToMobs[$mapName] = [];
                        }
                        if (!isset($mapToMobs[$mapName][$mobId])) {
                            $mapToMobs[$mapName][$mobId] = 0;
                        }
                        $mapToMobs[$mapName][$mobId] += max(1, $count);
                    }
                }
            }

            return [
                'mob_to_maps' => $mobToMaps,
                'map_to_mobs' => $mapToMobs,
            ];
        });
    }

    /**
     * Carrega lojas de NPCs geradas pela seed (SOTN Style Shops).
     */
    public function getNpcShops(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_npc_shops_{$seed}", 600, function () {
            $items = $this->getItems();
            $shopPaths = [
                $this->getDataPath('npc/pre-re/merchants/shops.txt'),
                $this->getDataPath('npc/merchants/shops.txt'),
            ];

            $shops = [];
            $itemToShops = [];

            foreach ($shopPaths as $path) {
                if (!$path || !file_exists($path)) {
                    continue;
                }

                $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (str_starts_with($line, '//') || empty(trim($line))) {
                        continue;
                    }

                    $parts = explode("\t", trim($line));
                    if (count($parts) >= 4 && $parts[1] === 'shop') {
                        $loc = explode(',', $parts[0]);
                        $map = trim($loc[0] ?? '');
                        $x = (int)($loc[1] ?? 0);
                        $y = (int)($loc[2] ?? 0);
                        $shopName = trim($parts[2]);

                        $rawItems = explode(',', $parts[3]);
                        $spriteId = $rawItems[0] ?? '';
                        $slotItems = array_slice($rawItems, 1);

                        $shopItemList = [];
                        foreach ($slotItems as $slot) {
                            $pair = explode(':', $slot);
                            $itemId = (int)($pair[0] ?? 0);
                            $price = (int)($pair[1] ?? -1);

                            if ($itemId <= 0) {
                                continue;
                            }

                            $baseBuy = $items[$itemId]['buy'] ?? 0;
                            $finalPrice = $price > 0 ? $price : $baseBuy;
                            $itemName = $items[$itemId]['name'] ?? "Item #{$itemId}";

                            $itemInfo = [
                                'item_id' => $itemId,
                                'item_name' => $itemName,
                                'price' => $finalPrice,
                                'sprite_url' => "https://static.divine-pride.net/images/items/item/{$itemId}.png",
                            ];

                            $shopItemList[] = $itemInfo;

                            if (!isset($itemToShops[$itemId])) {
                                $itemToShops[$itemId] = [];
                            }
                            $itemToShops[$itemId][] = [
                                'shop_name' => $shopName,
                                'map' => $map,
                                'x' => $x,
                                'y' => $y,
                                'price' => $finalPrice,
                            ];
                        }

                        $shops[] = [
                            'name' => $shopName,
                            'map' => $map,
                            'x' => $x,
                            'y' => $y,
                            'sprite_id' => $spriteId,
                            'items' => $shopItemList,
                        ];
                    }
                }
            }

            return [
                'shops' => $shops,
                'item_to_shops' => $itemToShops,
            ];
        });
    }

    /**
     * Consulta lojas ativas de jogadores (Vendings) nas tabelas do rAthena.
     */
    public function getPlayerVendings(?string $search = null): array
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('vendings')) {
                return [];
            }

            $query = DB::table('vendings as v')
                ->join('char as c', 'v.char_id', '=', 'c.char_id')
                ->join('vending_items as vi', 'v.id', '=', 'vi.vending_id')
                ->join('cart_inventory as ci', 'vi.cartinventory_id', '=', 'ci.id')
                ->select([
                    'v.id as vending_id',
                    'v.title as shop_title',
                    'v.map',
                    'v.x',
                    'v.y',
                    'c.name as merchant_name',
                    'vi.amount',
                    'vi.price',
                    'ci.nameid as item_id',
                    'ci.refine',
                    'ci.card0',
                    'ci.card1',
                    'ci.card2',
                    'ci.card3',
                ])
                ->orderByDesc('v.created');

            $items = $this->getItems();
            $results = $query->take(50)->get();

            $vendings = [];
            foreach ($results as $row) {
                $itemName = $items[$row->item_id]['name'] ?? "Item #{$row->item_id}";

                if ($search !== null && $search !== '') {
                    $s = strtolower($search);
                    if (!str_contains(strtolower($itemName), $s) && !str_contains((string)$row->item_id, $s)) {
                        continue;
                    }
                }

                $vendings[] = [
                    'vending_id' => $row->vending_id,
                    'shop_title' => $row->shop_title,
                    'merchant_name' => $row->merchant_name,
                    'map' => $row->map,
                    'x' => $row->x,
                    'y' => $row->y,
                    'item_id' => $row->item_id,
                    'item_name' => $itemName,
                    'amount' => $row->amount,
                    'price' => $row->price,
                    'refine' => $row->refine,
                    'cards' => array_filter([$row->card0, $row->card1, $row->card2, $row->card3]),
                    'sprite_url' => "https://static.divine-pride.net/images/items/item/{$row->item_id}.png",
                ];
            }

            return $vendings;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Motor do Meta Analyzer: analisa tendências dos itens e afixos gerados pela seed.
     */
    public function getMetaAnalysis(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_meta_analysis_{$seed}", 600, function () {
            $items = $this->getItems();
            $statsCount = [
                'bAtk' => 0,
                'bMatk' => 0,
                'bHit' => 0,
                'bCritical' => 0,
                'bFlee' => 0,
                'bDef' => 0,
                'bMdef' => 0,
                'bMaxHP' => 0,
                'bMaxSP' => 0,
                'bAspdRate' => 0,
            ];

            foreach ($items as $item) {
                if (empty($item['script'])) {
                    continue;
                }
                foreach (array_keys($statsCount) as $stat) {
                    if (str_contains($item['script'], $stat)) {
                        $statsCount[$stat] += substr_count($item['script'], $stat);
                    }
                }
            }

            $atk = $statsCount['bAtk'];
            $matk = $statsCount['bMatk'];
            $crit = $statsCount['bCritical'];
            $aspd = $statsCount['bAspdRate'];
            $hit = $statsCount['bHit'];
            $flee = $statsCount['bFlee'];
            $def = $statsCount['bDef'];
            $hp = $statsCount['bMaxHP'];

            // Interpretação do Meta Principal
            $primaryMeta = '⚖️ Balanceado (Sem viés dominante de classe)';
            if (($crit + $aspd) > ($atk + $matk) && ($crit + $aspd) > 0) {
                $primaryMeta = '⚡ Meta Focado em Crítico & ASPD (Ataques Velozes)';
            } elseif ($atk > $matk && $atk > 0) {
                $primaryMeta = '⚔️ Meta Focado em Dano Físico Bruto (Melee ATK)';
            } elseif ($matk > $atk && $matk > 0) {
                $primaryMeta = '🔥 Meta Focado em Magia e Arcano (MATK / SP)';
            }

            // Recomendações de Classes
            $recommended = [];
            if (($crit + $aspd) >= 3) {
                $recommended[] = ['class' => 'Mercenário & Knight Crítico', 'reason' => 'Alta disponibilidade de bCritical e bAspdRate em equipamentos'];
            }
            if ($atk >= 3) {
                $recommended[] = ['class' => 'Cavaleiro, Ferreiro & Monge', 'reason' => 'Bônus consistentes de ATK físico'];
            }
            if ($matk >= 3) {
                $recommended[] = ['class' => 'Bruxo & Sábio', 'reason' => 'Afixos mágicos e de MATK viabilizam conjuração rápida'];
            }
            if ($flee >= 3) {
                $recommended[] = ['class' => 'Arruaceiro & Caçador (Dodge/Flee)', 'reason' => 'Afixos de esquiva facilitam progressão solo'];
            }
            if (($def + $hp) >= 5) {
                $recommended[] = ['class' => 'Templário & Tank Builds', 'reason' => 'Excelente suporte para sobrevivência com bDef e bMaxHP'];
            }

            if (empty($recommended)) {
                $recommended[] = ['class' => 'Todas as Classes', 'reason' => 'Distribuição uniforme de afixos pela seed'];
            }

            // Diagnóstico de Sofrimento / Cursed
            $cursed = [];
            if ($matk == 0) {
                $cursed[] = ['type' => 'Magia Nula', 'desc' => 'Nenhum equipamento da seed fornece bMatk. Magos terão progressão difícil.'];
            }
            if ($crit == 0) {
                $cursed[] = ['type' => 'Crítico Escasso', 'desc' => 'Ausência de afixos de bCritical. Builds de Katar de Crítico desfavorecidas.'];
            }
            if ($flee == 0) {
                $cursed[] = ['type' => 'Esquiva Baixa', 'desc' => 'Falta de bFlee. Jogadores de agilidade precisarão de poções extras.'];
            }
            if ($hit == 0) {
                $cursed[] = ['type' => 'Precisão Normal', 'desc' => 'Sem bHit bônus, requer investimento manual em DES contra monstros ágeis.'];
            }

            return [
                'stats_count' => $statsCount,
                'primary_meta' => $primaryMeta,
                'recommended' => $recommended,
                'cursed' => $cursed,
            ];
        });
    }

    /**
     * Calculadora de Rotas de Grind e Leveling Guide baseado em EXP/HP e spawns.
     */
    public function getExpRoutes(): array
    {
        $seed = $this->getActiveSeed();

        return Cache::remember("world_exp_routes_{$seed}", 600, function () {
            $mobs = $this->getMobs();
            $spawns = $this->getMobSpawns()['map_to_mobs'] ?? [];

            $routes = [];

            foreach ($spawns as $mapName => $mobCounts) {
                $totalScore = 0;
                $weightedLevel = 0;
                $totalCount = 0;
                $mobPreviews = [];

                foreach ($mobCounts as $mobId => $count) {
                    $mob = $mobs[$mobId] ?? null;
                    if (!$mob) {
                        continue;
                    }

                    $baseExp = $mob['exp'];
                    $jobExp = $mob['jexp'];
                    $hp = $mob['hp'];
                    $lv = $mob['level'];

                    // Eficiência: (Base + Job) * Quantidade / (HP + 1)
                    $score = (($baseExp + $jobExp) * $count) / max(1, $hp + 1);
                    $totalScore += $score;
                    $weightedLevel += ($lv * $count);
                    $totalCount += $count;

                    $mobPreviews[] = [
                        'id' => $mobId,
                        'name' => $mob['name'],
                        'level' => $lv,
                        'count' => $count,
                        'icon_url' => $mob['icon_url'] ?? "https://static.divine-pride.net/images/mobs/png/{$mobId}.png",
                        'sprite_url' => $mob['sprite_url'],
                    ];
                }

                if ($totalCount > 0 && $totalScore > 0) {
                    $avgLevel = round($weightedLevel / $totalCount);

                    // Determina faixa de nível
                    $tier = 'Lv 1–25';
                    if ($avgLevel > 75) {
                        $tier = 'Lv 76–99';
                    } elseif ($avgLevel > 50) {
                        $tier = 'Lv 51–75';
                    } elseif ($avgLevel > 25) {
                        $tier = 'Lv 26–50';
                    }

                    $routes[] = [
                        'map' => $mapName,
                        'score' => round($totalScore, 1),
                        'avg_level' => $avgLevel,
                        'tier' => $tier,
                        'total_mobs' => $totalCount,
                        'mobs' => array_slice($mobPreviews, 0, 5),
                    ];
                }
            }

            // Ordena por score decrescente
            usort($routes, fn($a, $b) => $b['score'] <=> $a['score']);

            // Agrupa por Tier
            $grouped = [
                'Lv 1–25' => [],
                'Lv 26–50' => [],
                'Lv 51–75' => [],
                'Lv 76–99' => [],
            ];

            foreach ($routes as $route) {
                if (isset($grouped[$route['tier']])) {
                    $grouped[$route['tier']][] = $route;
                }
            }

            return [
                'all' => $routes,
                'tiers' => $grouped,
            ];
        });
    }

    /**
     * Coleta métricas gerais do banco de dados relacional.
     */
    public function getGeneralStats(): array
    {
        try {
            $accounts = DB::table('login')->count();
        } catch (\Throwable $e) {
            $accounts = 0;
        }

        try {
            $characters = Character::count();
            $zeny = Character::sum('zeny');
            $online = Character::where('online', 1)->count();
        } catch (\Throwable $e) {
            $characters = 0;
            $zeny = 0;
            $online = 0;
        }

        try {
            $mvpKills = EventMvpKill::count();
        } catch (\Throwable $e) {
            $mvpKills = 0;
        }

        return [
            'accounts' => $accounts,
            'characters' => $characters,
            'zeny' => number_format((float)$zeny, 0, ',', '.'),
            'online' => $online,
            'mvp_kills' => $mvpKills,
            'seed' => $this->getActiveSeed(),
        ];
    }

    /**
     * Verifica disponibilidade dos serviços do rAthena e web.
     */
    public function getServerStatus(): array
    {
        $checkSocket = function ($port) {
            $host = '127.0.0.1';
            $fp = @fsockopen($host, $port, $errno, $errstr, 0.2);
            if ($fp) {
                fclose($fp);
                return true;
            }
            return false;
        };

        return [
            'login' => $checkSocket(6900),
            'char' => $checkSocket(6121),
            'map' => $checkSocket(5121),
            'client' => $checkSocket(8001),
        ];
    }
}
