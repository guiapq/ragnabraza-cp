<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\WorldDataService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RandomEquipController extends Controller
{
    public function __construct(
        protected WorldDataService $worldData
    ) {}

    /**
     * Página dedicada de Equipamentos Randomizados.
     * Exibe a estrutura da Curva de Bell, regras Pre-Renewal e exemplos de equipamentos
     * gerados para o mundo atual com seus nomes anonimizados como "????????????".
     */
    public function index(Request $request): View
    {
        $activeSeed = $this->worldData->getActiveSeed();
        $dbPath = $this->worldData->getDataPath('db/pre-re/item_db.txt');

        $categories = [
            'comic_weapons' => [
                'title' => 'Armas Cômicas de Tiers Altos (Quebra de Regras)',
                'badge' => 'Tier 3 Alto Extravagante',
                'badge_color' => 'text-bg-danger',
                'description' => 'Afixos raros que ignoram propositalmente as restrições padrão de armas para efeitos hilários, autoconjuração de berros, provocações involuntárias ou velocidades extremas.',
                'items' => []
            ],
            'comic_armors' => [
                'title' => 'Armaduras e Vestimentas Cômicas (Quebra de Regras)',
                'badge' => 'Tier 3 Alto Extravagante',
                'badge_color' => 'text-bg-danger',
                'description' => 'Vestimentas que quebram tetos clássicos de Pre-Renewal (ultrapassando 15 de DEFM, 12% de redução neutra ou +3 AllStats) para utilidades peculiares de jogo.',
                'items' => []
            ],
            'elemental_weapons' => [
                'title' => 'Armas com Propriedade Elemental Intrínseca',
                'badge' => '20% das Armas',
                'badge_color' => 'text-bg-warning',
                'description' => 'Exatamente 20% das armas geradas pelo mundo adquirem um elemento natural fixo (Fogo, Água, Vento, Terra, Sagrado, Sombrio ou Fantasma), dispensando conversores elementais.',
                'items' => []
            ],
            'autocast_weapons' => [
                'title' => 'Armas de Autoconjuração Ofensiva',
                'badge' => 'Tiers 2 e 3 (Boa / Ótima)',
                'badge_color' => 'text-bg-primary',
                'description' => 'Armamentos que conjuram magias clássicas e habilidades marciais (Nv. 2 a 5) automaticamente a cada golpe desferido.',
                'items' => []
            ],
            'skill_accessories' => [
                'title' => 'Acessórios com Habilidades & Atributos Puros (Até +5)',
                'badge' => 'Acessórios Úteis',
                'badge_color' => 'text-bg-info',
                'description' => 'Acessórios que concedem o uso de habilidades ativas como Teleporte, Furto, Curar, Vigor ou Impacto Explosivo, além de pontos brutos de atributos até o teto de +5.',
                'items' => []
            ],
            'defensive_capes' => [
                'title' => 'Capas e Manteletes Defensivos',
                'badge' => 'Defensivo / Flee',
                'badge_color' => 'text-bg-success',
                'description' => 'Capas focadas em evasão (Esquiva elevada), resistências elementais percentuais até 20% e redução estrita de Neutro calibrada até 12%.',
                'items' => []
            ],
            'hybrid_shoes' => [
                'title' => 'Calçados de Sobrevivência & Ataque',
                'badge' => 'Sobrevivência + ATQ',
                'badge_color' => 'text-bg-secondary',
                'description' => 'Sapatos desenhados para sustentar o avanço solo com ampliação de HP Máximo, recuperação de SP, Agilidade e ATQ físico adicional.',
                'items' => []
            ],
            'body_armors' => [
                'title' => 'Armaduras de Corpo Defensivas & Autocast on-hit',
                'badge' => 'Pontos Defensivos',
                'badge_color' => 'text-bg-dark',
                'description' => 'Armaduras com Hard DEF contido (1 a 5), Vitalidade, HP elevado, resistências elementais e chance de autoconjurar defesas ao receber dano.',
                'items' => []
            ],
            'flawed_weapons' => [
                'title' => 'Armas Defeituosas / Ruim (15% da Curva de Bell)',
                'badge' => 'Tier 0 (Curva de Bell)',
                'badge_color' => 'text-bg-secondary',
                'description' => 'O fundo da curva de Gauss: armas com pequenos defeitos de forja, penalidades leves de precisão, esquiva ou dano que valorizam as armas bem-sucedidas.',
                'items' => []
            ],
        ];

        if (file_exists($dbPath)) {
            $lines = file($dbPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with($line, '//')) continue;
                $cols = explode(',', $line);
                if (count($cols) < 18) continue;

                $type = (int)($cols[3] ?? 0);
                $loc = (int)($cols[14] ?? 0);
                $elv = (int)($cols[16] ?? 0);

                if (!in_array($type, [4, 5])) continue;

                $start = strpos($line, '{');
                $end = strrpos($line, '}');
                $script = ($start !== false && $end !== false) ? substr($line, $start, $end - $start + 1) : '';

                if (empty($script)) continue;

                $slotName = $this->resolveSlotName($type, $loc);
                $parsedBonuses = $this->parseBonuses($script);

                if (empty($parsedBonuses)) continue;

                // 1. Armas Cômicas
                if ($type === 5 && count($categories['comic_weapons']['items']) < 5) {
                    if (preg_match('/bSpeedRate,35|MC_LOUD|SM_PROVOKE|WZ_METEOR|bDef,8|bLuk,30/', $script)) {
                        $categories['comic_weapons']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 2. Armaduras Cômicas
                if ($type === 4 && count($categories['comic_armors']['items']) < 5) {
                    if (preg_match('/bSpeedRate,40|Ele_Neutral,25|bAllStats,5|bMdef,22|MC_LOUD/', $script)) {
                        $categories['comic_armors']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 3. Armas com elemento
                if ($type === 5 && count($categories['elemental_weapons']['items']) < 5) {
                    if (preg_match('/bAtkEle,Ele_/', $script)) {
                        $categories['elemental_weapons']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 4. Armas com autocast
                if ($type === 5 && count($categories['autocast_weapons']['items']) < 5) {
                    if (str_contains($script, 'bAutoSpell,')) {
                        $categories['autocast_weapons']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 5. Acessórios com skills
                if ($type === 4 && ($loc & 136 || in_array($loc, [8, 128])) && count($categories['skill_accessories']['items']) < 5) {
                    if (str_contains($script, 'skill ') || preg_match('/b(Str|Agi|Vit|Int|Dex|Luk),[4-5];/', $script)) {
                        $categories['skill_accessories']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 6. Capas
                if ($type === 4 && ($loc & 4) && count($categories['defensive_capes']['items']) < 5) {
                    if (preg_match('/bSubEle,Ele_Neutral,([4-9]|1[0-2]);/', $script)) {
                        $categories['defensive_capes']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 7. Sapatos
                if ($type === 4 && ($loc & 64) && count($categories['hybrid_shoes']['items']) < 5) {
                    if (str_contains($script, 'bAtk,')) {
                        $categories['hybrid_shoes']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 8. Armaduras de corpo
                if ($type === 4 && ($loc & 16) && count($categories['body_armors']['items']) < 5) {
                    if (str_contains($script, 'bAutoSpellWhenHit') || preg_match('/bSubEle,Ele_(Fire|Water|Wind|Earth)/', $script)) {
                        $categories['body_armors']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }

                // 9. Armas ruins (Tier 0)
                if ($type === 5 && count($categories['flawed_weapons']['items']) < 5) {
                    if (preg_match('/bonus bAtk,[1-3]; bonus b(Hit|Critical|Agi|Str|Def),-[1-3];/', $script)) {
                        $categories['flawed_weapons']['items'][] = [
                            'name' => '????????????',
                            'slot' => $slotName,
                            'elv' => $elv,
                            'bonuses' => $parsedBonuses,
                        ];
                        continue;
                    }
                }
            }
        }

        return view('random_equips', compact('activeSeed', 'categories'));
    }

    protected function resolveSlotName(int $type, int $loc): string
    {
        if ($type === 5) {
            return 'Armamento';
        }
        if ($loc & 16) return 'Armadura de Corpo';
        if ($loc & 64) return 'Sapatos / Calçado';
        if ($loc & 4) return 'Capa / Mantelete';
        if ($loc & 32) return 'Escudo';
        if ($loc & 136 || in_array($loc, [8, 128])) return 'Acessório';
        if ($loc & 256 || $loc & 512 || $loc & 1) return 'Equip. para Cabeça';
        return 'Equipamento';
    }

    protected function parseBonuses(string $script): array
    {
        $bonuses = [];

        $skillNames = [
            'AL_HEAL' => 'Curar',
            'AL_BLESSING' => 'Bênção',
            'AL_INCAGI' => 'Aumentar Agilidade',
            'AL_TELEPORT' => 'Teleporte',
            'AL_CURE' => 'Curar Efeitos',
            'PR_KYRIE' => 'Kyrie Eleison',
            'SM_ENDURE' => 'Vigor',
            'SM_BASH' => 'Golpe Fulminante',
            'SM_MAGNUM' => 'Impacto Explosivo',
            'SM_PROVOKE' => 'Provocar',
            'MG_FIREBOLT' => 'Lanças de Fogo',
            'MG_COLDBOLT' => 'Lanças de Gelo',
            'MG_LIGHTNINGBOLT' => 'Relâmpago',
            'TF_DOUBLE' => 'Ataque Duplo',
            'TF_STEAL' => 'Furto',
            'TF_HIDING' => 'Esconderijo',
            'MC_MAMMONITE' => 'Mammonita',
            'MC_LOUD' => 'Grito de Guerra',
            'WZ_METEOR' => 'Chuva de Meteoros',
        ];

        // Autocast on attack
        if (preg_match_all('/bonus3\s+bAutoSpell,"([^"]+)",(\d+),(\d+);/', $script, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $sk = $skillNames[$m[1]] ?? $m[1];
                $pct = (int)$m[3] / 10;
                $bonuses[] = "Autoconjura {$sk} Nv. {$m[2]} ao atacar ({$pct}%)";
            }
        }

        // Autocast on hit
        if (preg_match_all('/bonus3\s+bAutoSpellWhenHit,"([^"]+)",(\d+),(\d+);/', $script, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $sk = $skillNames[$m[1]] ?? $m[1];
                $pct = (int)$m[3] / 10;
                $bonuses[] = "Autoconjura {$sk} Nv. {$m[2]} ao sofrer dano ({$pct}%)";
            }
        }

        // Skill concedida
        if (preg_match_all('/skill\s+"([^"]+)",(\d+);/', $script, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $sk = $skillNames[$m[1]] ?? $m[1];
                $bonuses[] = "Habilita o uso de {$sk} Nv. {$m[2]}";
            }
        }

        // Elemento da arma
        if (preg_match('/bonus\s+bAtkEle,Ele_(\w+);/', $script, $m)) {
            $elements = [
                'Fire' => 'Fogo', 'Water' => 'Água', 'Wind' => 'Vento', 'Earth' => 'Terra',
                'Holy' => 'Sagrado', 'Dark' => 'Sombrio', 'Ghost' => 'Fantasma', 'Poison' => 'Veneno'
            ];
            $eleName = $elements[$m[1]] ?? $m[1];
            $bonuses[] = "Arma com Propriedade {$eleName}";
        }

        // Reduções elementais
        if (preg_match_all('/bonus2\s+bSubEle,Ele_(\w+),([-\d]+);/', $script, $matches, PREG_SET_ORDER)) {
            $elements = [
                'Neutral' => 'Neutro', 'Fire' => 'Fogo', 'Water' => 'Água', 'Wind' => 'Vento', 'Earth' => 'Terra',
            ];
            foreach ($matches as $m) {
                $eleName = $elements[$m[1]] ?? $m[1];
                $bonuses[] = "Resistência a {$eleName} +{$m[2]}%";
            }
        }

        // Atributos e combate
        $patterns = [
            '/bonus\s+bAtk,([-\d]+);/' => 'ATQ %s',
            '/bonus\s+bDef,([-\d]+);/' => 'DEF %s',
            '/bonus\s+bMdef,([-\d]+);/' => 'DEFM %s',
            '/bonus\s+bHit,([-\d]+);/' => 'Precisão %s',
            '/bonus\s+bFlee,([-\d]+);/' => 'Esquiva %s',
            '/bonus\s+bCritical,([-\d]+);/' => 'Crítico %s',
            '/bonus\s+bCritAtkRate,([-\d]+);/' => 'Dano Crítico +%s%%',
            '/bonus\s+bAspdRate,([-\d]+);/' => 'Vel. de Ataque +%s%%',
            '/bonus\s+bSpeedRate,([-\d]+);/' => 'Vel. de Movimento +%s%%',
            '/bonus\s+bMaxHP,([-\d]+);/' => 'HP Máximo %s',
            '/bonus\s+bMaxSP,([-\d]+);/' => 'SP Máximo %s',
            '/bonus\s+bAllStats,([-\d]+);/' => 'Todos os Atributos %s',
            '/bonus\s+bStr,([-\d]+);/' => 'Força %s',
            '/bonus\s+bAgi,([-\d]+);/' => 'Agilidade %s',
            '/bonus\s+bVit,([-\d]+);/' => 'Vitalidade %s',
            '/bonus\s+bInt,([-\d]+);/' => 'Inteligência %s',
            '/bonus\s+bDex,([-\d]+);/' => 'Destreza %s',
            '/bonus\s+bLuk,([-\d]+);/' => 'Sorte %s',
        ];

        foreach ($patterns as $regex => $label) {
            if (preg_match($regex, $script, $m)) {
                $val = (int)$m[1];
                $valStr = $val > 0 ? "+{$val}" : (string)$val;
                $bonuses[] = sprintf($label, $valStr);
            }
        }

        return array_unique($bonuses);
    }
}
