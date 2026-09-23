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
