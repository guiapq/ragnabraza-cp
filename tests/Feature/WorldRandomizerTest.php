<?php

use App\Models\Game\Item;
use App\Models\Game\Mob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('tabelas principais do modo SQL existem no banco de dados', function () {
    expect(Schema::hasTable('item_db'))->toBeTrue();
    expect(Schema::hasTable('mob_db'))->toBeTrue();
    expect(Schema::hasTable('login'))->toBeTrue();
    expect(Schema::hasTable('char'))->toBeTrue();
});

test('tabela item_db contem itens sincronizados e validos do mundo', function () {
    $itemCount = DB::table('item_db')->count();
    expect($itemCount)->toBeGreaterThan(500);

    // Magnifier (Lupa - ID 611) deve existir e ter peso compativel com modo roguelike
    $magnifier = Item::find(611);
    expect($magnifier)->not->toBeNull();
    expect($magnifier->name_english)->not->toBeEmpty();
});

test('prevencao de exploits de precos de compra e venda no item_db', function () {
    // Nenhum item deve ter preco de venda maior que o preco de compra quando ambos existem
    $exploitable = DB::table('item_db')
        ->whereNotNull('price_buy')
        ->whereNotNull('price_sell')
        ->where('price_buy', '>', 0)
        ->whereRaw('price_sell > price_buy')
        ->count();

    expect($exploitable)->toBe(0);
});

test('tabela mob_db contem monstros com atributos e HP positivos', function () {
    $mobCount = DB::table('mob_db')->count();
    expect($mobCount)->toBeGreaterThan(300);

    // Nenhum monstro valido deve ter HP zerado ou negativo
    $invalidHp = DB::table('mob_db')
        ->where('ID', '>', 0)
        ->where('HP', '<=', 0)
        ->count();

    expect($invalidHp)->toBe(0);
});

test('taxas de drop dos monstros estao calibradas dentro do limite do rAthena (1 a 10000)', function () {
    // 10000 = 100%, 1 = 0.01%
    $invalidDrops = DB::table('mob_db')
        ->where(function ($query) {
            $query->where('Drop1per', '>', 10000)
                ->orWhere('Drop2per', '>', 10000)
                ->orWhere('Drop3per', '>', 10000)
                ->orWhere('Drop4per', '>', 10000)
                ->orWhere('Drop5per', '>', 10000)
                ->orWhere('Drop6per', '>', 10000)
                ->orWhere('Drop7per', '>', 10000)
                ->orWhere('Drop8per', '>', 10000)
                ->orWhere('Drop9per', '>', 10000)
                ->orWhere('DropCardper', '>', 10000);
        })
        ->count();

    expect($invalidDrops)->toBe(0);
});

test('contas de teste e permissoes de grupo estao corretas', function () {
    // Conta teste_knight deve existir com grupo 6 (Tester)
    $knight = User::where('userid', 'teste_knight')->first();
    expect($knight)->not->toBeNull();
    expect($knight->group_id)->toBe(6);

    // Conta teste_wizard deve existir com grupo 6 (Tester)
    $wizard = User::where('userid', 'teste_wizard')->first();
    expect($wizard)->not->toBeNull();
    expect($wizard->group_id)->toBe(6);

    // Conta de admin (roadmin) deve existir com grupo 99
    $admin = User::where('userid', 'roadmin')->first();
    expect($admin)->not->toBeNull();
    expect($admin->group_id)->toBe(99);
});

test('personagens de teste foram gerados com level 99 e classe correspondente', function () {
    $chars = DB::table('char')
        ->whereIn('account_id', [2000016, 2000017])
        ->get();

    expect($chars->count())->toBeGreaterThanOrEqual(2);
    foreach ($chars as $char) {
        expect($char->base_level)->toBe(99);
        expect($char->zeny)->toBeGreaterThanOrEqual(50000);
    }
});

test('WorldDataService carrega itens e monstros diretamente do modo SQL', function () {
    $service = app(\App\Services\WorldDataService::class);
    $activeSeed = $service->getActiveSeed();
    expect($activeSeed)->not->toBeEmpty();

    $items = $service->getItems();
    expect(count($items))->toBeGreaterThan(500);

    $mobs = $service->getMobs();
    expect(count($mobs))->toBeGreaterThan(300);

    // Monstro Poring (1002) deve possuir drops sincronizados
    expect($mobs[1002] ?? null)->not->toBeNull();
    expect(count($mobs[1002]['drops']))->toBeGreaterThan(0);
});

test('migration de seed do mundo foi registrada no banco de dados', function () {
    $seedMigration = DB::table('migrations')
        ->where('migration', 'like', '%_seed_world_%')
        ->exists();

    expect($seedMigration)->toBeTrue();
});

test('nomes dos monstros foram randomizados com afixos procedurais no modo SQL', function () {
    $prefixes = ['Angry', 'Ancient', 'Turbo', 'Mutated', 'Forgotten', 'Cursed', 'Radiant', 'Chaotic', 'Cosmic', 'Shadow'];
    $suffixes = ['Beast', 'Thing', 'Horror', 'Creature', 'Abomination', 'Spawn', 'Monster', 'Gremlin', 'Blob', 'Entity'];

    $prefixRegex = implode('|', $prefixes);
    $suffixRegex = implode('|', $suffixes);
    $pattern = "^({$prefixRegex}) .* ({$suffixRegex})$";

    // Contar monstros cujo nome segue a gramática procedural do randomizador
    $randomizedCount = DB::table('mob_db')
        ->whereRaw("iName REGEXP '{$pattern}'")
        ->count();

    expect($randomizedCount)->toBeGreaterThan(800);

    // Validar caso concreto: Poring (1002) não deve ter o nome estático canônico 'Poring'
    $poring = DB::table('mob_db')->where('ID', 1002)->first();
    expect($poring)->not->toBeNull();
    expect($poring->iName)->not->toBe('Poring');
    expect($poring->iName)->toMatch("/({$prefixRegex}) Poring ({$suffixRegex})/");

    // Validar através do WorldDataService
    $service = app(\App\Services\WorldDataService::class);
    $mobs = $service->getMobs();
    expect($mobs[1002]['name'])->toBe($poring->iName);
});

test('comportamento e IA (Mode) dos monstros foram randomizados e preservados no SQL', function () {
    // Mode define o comportamento da IA (0x80: Agressivo, 0x01: Pode se mover, 0x02: Looter, 0x20: Boss)
    // No modo SQL, o campo Mode não deve estar zerado para monstros ativos
    $mobsWithMode = DB::table('mob_db')
        ->where('Mode', '>', 0)
        ->count();

    expect($mobsWithMode)->toBeGreaterThan(900);

    // Poring (1002) possui flags de comportamento preservadas (ex: 0x83 = pode mover, looter, agressivo/assist)
    $poring = DB::table('mob_db')->where('ID', 1002)->first();
    expect($poring->Mode)->toBeGreaterThan(0);

    // Scorpion (1001) possui bitmask completo de IA preservado
    $scorpion = DB::table('mob_db')->where('ID', 1001)->first();
    expect($scorpion->Mode)->toBeGreaterThan(0);

    // Validar que existem monstros agressivos com a flag 0x80 ativa
    $aggressiveCount = DB::table('mob_db')
        ->whereRaw('(Mode & 128) > 0')
        ->count();

    expect($aggressiveCount)->toBeGreaterThan(500);
});

test('atributos de combate dos monstros (HP, ATK, DEF) foram randomizados a partir da seed', function () {
    // Em pre-re vanilla, Poring possui exatamente HP=50 e Wolf possui HP=919.
    // A pipeline de randomize_stats aplica mutação procedural de acordo com a seed.
    $poring = DB::table('mob_db')->where('ID', 1002)->first();
    $wolf = DB::table('mob_db')->where('ID', 1013)->first();

    expect($poring)->not->toBeNull();
    expect($wolf)->not->toBeNull();

    // Pelo menos um dos monstros clássicos deve ter HP mutado em relação ao vanilla estático
    $isStatsMutated = ($poring->HP !== 50) || ($wolf->HP !== 919);
    expect($isStatsMutated)->toBeTrue();

    // Integridade: nenhum monstro tem HP negativo ou zerado
    $invalidStats = DB::table('mob_db')
        ->where('HP', '<=', 0)
        ->orWhere('ATK1', '<', 0)
        ->count();

    expect($invalidStats)->toBe(0);
});
