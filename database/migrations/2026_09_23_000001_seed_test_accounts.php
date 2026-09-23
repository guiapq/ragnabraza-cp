<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('login')) {
            return;
        }

        $now = now();

        $accounts = [
            'roadmin'          => ['id' => 2000001, 'pass' => 'roadmin',  'group' => 99, 'email' => 'admin@ragnarogue.local'],
            'teste_swordie'    => ['id' => 2000010, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_swordie@test.local'],
            'teste_mage'       => ['id' => 2000011, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_mage@test.local'],
            'teste_archer'     => ['id' => 2000012, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_archer@test.local'],
            'teste_acolyte'    => ['id' => 2000013, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_acolyte@test.local'],
            'teste_merchant'   => ['id' => 2000014, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_merchant@test.local'],
            'teste_thief'      => ['id' => 2000015, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_thief@test.local'],
            'teste_knight'     => ['id' => 2000016, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_knight@test.local'],
            'teste_wizard'     => ['id' => 2000017, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_wizard@test.local'],
            'teste_priest'     => ['id' => 2000018, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_priest@test.local'],
            'teste_blacksmith' => ['id' => 2000019, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_blacksmith@test.local'],
            'teste_hunter'     => ['id' => 2000020, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_hunter@test.local'],
            'teste_assassin'   => ['id' => 2000021, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_assassin@test.local'],
            'teste_extended'   => ['id' => 2000022, 'pass' => 'teste123', 'group' => 6,  'email' => 'teste_extended@test.local'],
        ];

        foreach ($accounts as $userid => $data) {
            DB::table('login')->updateOrInsert(
                ['account_id' => $data['id']],
                [
                    'userid'          => $userid,
                    'user_pass'       => $data['pass'],
                    'sex'             => 'M',
                    'email'           => $data['email'] ?? "{$userid}@test.local",
                    'group_id'        => $data['group'] ?? 6,
                    'birthdate'       => '2000-01-01',
                    'character_slots' => 9,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]
            );
        }

        if (!Schema::hasTable('char')) {
            return;
        }

        // Definição dos personagens de teste
        $characters = [
            // 1ª Classes
            ['acct_id' => 2000010, 'char_num' => 0, 'name' => 'Swordie Test', 'class' => 1, 'base' => 99, 'job' => 50, 'str' => 82, 'agi' => 65, 'vit' => 45, 'int' => 1,  'dex' => 45, 'luk' => 1,  'hp' => 8500,  'sp' => 300],
            ['acct_id' => 2000011, 'char_num' => 0, 'name' => 'Mage Test',    'class' => 2, 'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 9,  'vit' => 30, 'int' => 95, 'dex' => 75, 'luk' => 1,  'hp' => 3200,  'sp' => 1100],
            ['acct_id' => 2000012, 'char_num' => 0, 'name' => 'Archer Test',  'class' => 3, 'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 80, 'vit' => 25, 'int' => 15, 'dex' => 90, 'luk' => 25, 'hp' => 4500,  'sp' => 500],
            ['acct_id' => 2000013, 'char_num' => 0, 'name' => 'Acolyte Test', 'class' => 4, 'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 20, 'vit' => 50, 'int' => 85, 'dex' => 60, 'luk' => 1,  'hp' => 5200,  'sp' => 1150],
            ['acct_id' => 2000014, 'char_num' => 0, 'name' => 'Merchant Test','class' => 5, 'base' => 99, 'job' => 50, 'str' => 82, 'agi' => 65, 'vit' => 45, 'int' => 1,  'dex' => 45, 'luk' => 1,  'hp' => 6800,  'sp' => 350],
            ['acct_id' => 2000015, 'char_num' => 0, 'name' => 'Thief Test',   'class' => 6, 'base' => 99, 'job' => 50, 'str' => 70, 'agi' => 85, 'vit' => 30, 'int' => 1,  'dex' => 42, 'luk' => 15, 'hp' => 5500,  'sp' => 300],

            // 2ª Classes & Transclasses
            ['acct_id' => 2000016, 'char_num' => 0, 'name' => 'Knight Test',      'class' => 7,    'base' => 99, 'job' => 50, 'str' => 85, 'agi' => 75, 'vit' => 50, 'int' => 1,  'dex' => 45, 'luk' => 9,  'hp' => 12000, 'sp' => 450],
            ['acct_id' => 2000016, 'char_num' => 1, 'name' => 'Lord Knight Test', 'class' => 4008, 'base' => 99, 'job' => 70, 'str' => 88, 'agi' => 70, 'vit' => 60, 'int' => 1,  'dex' => 45, 'luk' => 1,  'hp' => 16500, 'sp' => 550],

            ['acct_id' => 2000017, 'char_num' => 0, 'name' => 'Wizard Test',      'class' => 9,    'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 9,  'vit' => 35, 'int' => 99, 'dex' => 85, 'luk' => 9,  'hp' => 4200,  'sp' => 1500],
            ['acct_id' => 2000017, 'char_num' => 1, 'name' => 'High Wizard Test', 'class' => 4010, 'base' => 99, 'job' => 70, 'str' => 1,  'agi' => 9,  'vit' => 40, 'int' => 99, 'dex' => 90, 'luk' => 9,  'hp' => 5500,  'sp' => 1900],

            ['acct_id' => 2000018, 'char_num' => 0, 'name' => 'Priest Test',      'class' => 8,    'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 9,  'vit' => 60, 'int' => 92, 'dex' => 70, 'luk' => 9,  'hp' => 7500,  'sp' => 1650],
            ['acct_id' => 2000018, 'char_num' => 1, 'name' => 'High Priest Test', 'class' => 4009, 'base' => 99, 'job' => 70, 'str' => 1,  'agi' => 9,  'vit' => 65, 'int' => 95, 'dex' => 75, 'luk' => 9,  'hp' => 9500,  'sp' => 2100],

            ['acct_id' => 2000019, 'char_num' => 0, 'name' => 'Blacksmith Test',  'class' => 10,   'base' => 99, 'job' => 50, 'str' => 85, 'agi' => 75, 'vit' => 45, 'int' => 1,  'dex' => 45, 'luk' => 2,  'hp' => 9000,  'sp' => 480],
            ['acct_id' => 2000019, 'char_num' => 1, 'name' => 'Whitesmith Test',  'class' => 4011, 'base' => 99, 'job' => 70, 'str' => 90, 'agi' => 75, 'vit' => 50, 'int' => 1,  'dex' => 45, 'luk' => 2,  'hp' => 12000, 'sp' => 600],

            ['acct_id' => 2000020, 'char_num' => 0, 'name' => 'Hunter Test',      'class' => 11,   'base' => 99, 'job' => 50, 'str' => 9,  'agi' => 85, 'vit' => 30, 'int' => 20, 'dex' => 90, 'luk' => 35, 'hp' => 6000,  'sp' => 650],
            ['acct_id' => 2000020, 'char_num' => 1, 'name' => 'Sniper Test',      'class' => 4012, 'base' => 99, 'job' => 70, 'str' => 9,  'agi' => 90, 'vit' => 30, 'int' => 20, 'dex' => 95, 'luk' => 40, 'hp' => 7800,  'sp' => 850],

            ['acct_id' => 2000021, 'char_num' => 0, 'name' => 'Assassin Test',    'class' => 12,   'base' => 99, 'job' => 50, 'str' => 78, 'agi' => 85, 'vit' => 35, 'int' => 1,  'dex' => 40, 'luk' => 42, 'hp' => 7800,  'sp' => 420],
            ['acct_id' => 2000021, 'char_num' => 1, 'name' => 'Assassin Cross Test','class' => 4013,'base' => 99, 'job' => 70, 'str' => 85, 'agi' => 85, 'vit' => 40, 'int' => 1,  'dex' => 40, 'luk' => 42, 'hp' => 10500, 'sp' => 550],

            // Classes Expandidas
            ['acct_id' => 2000022, 'char_num' => 0, 'name' => 'Gunslinger Test',  'class' => 24,   'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 85, 'vit' => 40, 'int' => 15, 'dex' => 92, 'luk' => 10, 'hp' => 6500,  'sp' => 600],
            ['acct_id' => 2000022, 'char_num' => 1, 'name' => 'Soul Linker Test', 'class' => 4049, 'base' => 99, 'job' => 50, 'str' => 1,  'agi' => 9,  'vit' => 55, 'int' => 95, 'dex' => 75, 'luk' => 1,  'hp' => 7500,  'sp' => 1500],
            ['acct_id' => 2000022, 'char_num' => 2, 'name' => 'Star Glad Test',   'class' => 4047, 'base' => 99, 'job' => 50, 'str' => 85, 'agi' => 80, 'vit' => 45, 'int' => 1,  'dex' => 50, 'luk' => 1,  'hp' => 9000,  'sp' => 500],
            ['acct_id' => 2000022, 'char_num' => 3, 'name' => 'SuperNovice Test', 'class' => 23,   'base' => 99, 'job' => 99, 'str' => 60, 'agi' => 80, 'vit' => 30, 'int' => 50, 'dex' => 60, 'luk' => 10, 'hp' => 4000,  'sp' => 700],
        ];

        // Tentar localizar skill_tree.txt
        $skillTreePath = null;
        $skillCandidates = [
            base_path('game-data/db/pre-re/skill_tree.txt'),
            base_path('../data/db/pre-re/skill_tree.txt'),
            '/opt/rathena/db/pre-re/skill_tree.txt',
        ];
        foreach ($skillCandidates as $cand) {
            if (file_exists($cand)) {
                $skillTreePath = $cand;
                break;
            }
        }

        $skillTree = [];
        if ($skillTreePath) {
            $lines = file($skillTreePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '//')) {
                    continue;
                }
                $parts = preg_split('~[,/]~', $line);
                if (count($parts) >= 3) {
                    $classId = (int)$parts[0];
                    $skillId = (int)$parts[1];
                    $maxLv   = (int)$parts[2];
                    if ($skillId > 0 && $maxLv > 0) {
                        $skillTree[$classId][] = ['id' => $skillId, 'lv' => $maxLv];
                    }
                }
            }
        }

        foreach ($characters as $char) {
            $existing = DB::table('char')
                ->where('account_id', $char['acct_id'])
                ->where('char_num', $char['char_num'])
                ->first();

            if (!$existing) {
                $charId = DB::table('char')->insertGetId([
                    'account_id'   => $char['acct_id'],
                    'char_num'     => $char['char_num'],
                    'name'         => $char['name'],
                    'class'        => $char['class'],
                    'base_level'   => $char['base'],
                    'job_level'    => $char['job'],
                    'zeny'         => 50000,
                    'str'          => $char['str'],
                    'agi'          => $char['agi'],
                    'vit'          => $char['vit'],
                    'int'          => $char['int'],
                    'dex'          => $char['dex'],
                    'luk'          => $char['luk'],
                    'max_hp'       => $char['hp'],
                    'hp'           => $char['hp'],
                    'max_sp'       => $char['sp'],
                    'sp'           => $char['sp'],
                    'status_point' => 15,
                    'skill_point'  => 0,
                    'hair'         => 1,
                    'hair_color'   => 4,
                    'last_map'     => 'prontera',
                    'last_x'       => 155,
                    'last_y'       => 185,
                    'save_map'     => 'prontera',
                    'save_x'       => 155,
                    'save_y'       => 185,
                    'sex'          => 'M',
                    'settings'     => '',
                ]);
            } else {
                $charId = $existing->char_id;
            }

            // Inserir habilidades de classe se a tabela `skill` existir
            if (Schema::hasTable('skill') && isset($skillTree[$char['class']])) {
                foreach ($skillTree[$char['class']] as $sk) {
                    DB::table('skill')->updateOrInsert(
                        ['char_id' => $charId, 'id' => $sk['id']],
                        ['lv' => $sk['lv'], 'flag' => 0]
                    );
                }
            }

            // Inserir consumíveis e equipamentos leves se `inventory` existir
            if (Schema::hasTable('inventory')) {
                $invCount = DB::table('inventory')->where('char_id', $charId)->count();
                if ($invCount === 0) {
                    $commonItems = [
                        501   => 30, // Red Potion
                        505   => 15, // Blue Potion
                        601   => 20, // Fly Wing
                        602   => 5,  // Butterfly Wing
                        611   => 5,  // Magnifier
                        12103 => 5,  // Official Emblem
                    ];
                    foreach ($commonItems as $nameid => $amount) {
                        DB::table('inventory')->insertOrIgnore([
                            'char_id'  => $charId,
                            'nameid'   => $nameid,
                            'amount'   => $amount,
                            'equip'    => 0,
                            'identify' => 1,
                            'refine'   => 0,
                            'card0'    => 0,
                            'card1'    => 0,
                            'card2'    => 0,
                            'card3'    => 0,
                        ]);
                    }

                    // Equipamentos básicos por classe
                    $classGear = match ($char['class']) {
                        1    => [ // Swordman: Falchion, Buckler, Chain Mail, Shoes, Muffler
                            ['id' => 1102, 'equip' => 2],
                            ['id' => 2102, 'equip' => 32],
                            ['id' => 2315, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        7    => [ // Knight: Two-Handed Sword, Chain Mail, Shoes, Muffler
                            ['id' => 1113, 'equip' => 34],
                            ['id' => 2315, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        4008 => [ // Lord Knight: Claymore, Legion Plate, Shoes, Muffler
                            ['id' => 1162, 'equip' => 34],
                            ['id' => 2341, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        2    => [ // Mage: Wand, Guard, Silk Robe, Shoes, Muffler
                            ['id' => 1605, 'equip' => 2],
                            ['id' => 2101, 'equip' => 32],
                            ['id' => 2320, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        9, 4010 => [ // Wizard / High Wizard: Arc Wand, Guard, Silk Robe, Shoes, Muffler
                            ['id' => 1613, 'equip' => 2],
                            ['id' => 2101, 'equip' => 32],
                            ['id' => 2320, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        3    => [ // Archer: Composite Bow, Arrows x500, Tights, Shoes, Muffler
                            ['id' => 1704, 'equip' => 34],
                            ['id' => 1750, 'equip' => 0, 'amount' => 500],
                            ['id' => 2321, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        11, 4012 => [ // Hunter / Sniper: Hunter Bow, Arrows x1000, Tights, Shoes, Muffler
                            ['id' => 1714, 'equip' => 34],
                            ['id' => 1750, 'equip' => 0, 'amount' => 1000],
                            ['id' => 2321, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        6    => [ // Thief: Main Gauche, Guard, Tights, Shoes, Muffler
                            ['id' => 1207, 'equip' => 2],
                            ['id' => 2101, 'equip' => 32],
                            ['id' => 2321, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        12, 4013 => [ // Assassin / Assassin Cross: Katar, Tights, Shoes, Muffler
                            ['id' => 1250, 'equip' => 34],
                            ['id' => 2321, 'equip' => 16],
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ],
                        default => [
                            ['id' => 2401, 'equip' => 64],
                            ['id' => 2502, 'equip' => 4],
                        ]
                    };

                    foreach ($classGear as $g) {
                        DB::table('inventory')->insertOrIgnore([
                            'char_id'  => $charId,
                            'nameid'   => $g['id'],
                            'amount'   => $g['amount'] ?? 1,
                            'equip'    => $g['equip'] ?? 0,
                            'identify' => 1,
                            'refine'   => 0,
                            'card0'    => 0,
                            'card1'    => 0,
                            'card2'    => 0,
                            'card3'    => 0,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $testAccountIds = range(2000010, 2000022);

        if (Schema::hasTable('char')) {
            $charIds = DB::table('char')->whereIn('account_id', $testAccountIds)->pluck('char_id');

            if (Schema::hasTable('inventory')) {
                DB::table('inventory')->whereIn('char_id', $charIds)->delete();
            }
            if (Schema::hasTable('skill')) {
                DB::table('skill')->whereIn('char_id', $charIds)->delete();
            }

            DB::table('char')->whereIn('account_id', $testAccountIds)->delete();
        }

        if (Schema::hasTable('login')) {
            DB::table('login')->whereIn('account_id', $testAccountIds)->delete();
        }
    }
};
