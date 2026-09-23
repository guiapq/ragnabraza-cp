<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mercado & Enciclopédia de Itens — {{ config('app.name', 'RagnaRogue') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Styles & Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">
</head>
<body class="antialiased">
<x-banner/>
<x-navigation-menu/>

<div class="container py-4">
    {{-- Header / Hero da Página --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 shadow-sm hero-solarized" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border: 1px solid var(--sol-border);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge text-bg-warning font-monospace text-uppercase mb-2">Seed Ativa: {{ $activeSeed }}</span>
                <h1 class="display-6 fw-bold mb-2" style="color: #002b36;">Mercado Livre & Database de Itens</h1>
                <p class="lead mb-0 fs-6" style="color: #586e75;">
                    Pesquise lojas de mercadores ao vivo, vendedores de NPCs e monstros que dropam cada item com as taxas recalculadas da seed procedural.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-warning fw-bold px-4 py-2 shadow-sm">
                    Jogar no roBrowser
                </a>
            </div>
        </div>
    </div>

    {{-- Caixa de Busca --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('market.index') }}" class="row g-2">
                <div class="col-md-9">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text font-monospace">Item:</span>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search }}"
                            placeholder="Digite o nome do item (ex: Poring Card, Sabre, Poção Vermelha) ou ID..."
                            class="form-control font-monospace"
                            autofocus
                        >
                    </div>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">
                        Buscar no Mundo
                    </button>
                </div>
            </form>

            <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top align-items-center">
                <small class="text-secondary me-2">Atalhos Populares:</small>
                <a href="{{ route('market.index', ['q' => 'Card']) }}" class="badge text-bg-secondary text-decoration-none">Cartas</a>
                <a href="{{ route('market.index', ['q' => 'Poção']) }}" class="badge text-bg-secondary text-decoration-none">Poções</a>
                <a href="{{ route('market.index', ['q' => 'Espada']) }}" class="badge text-bg-secondary text-decoration-none">Espadas</a>
                <a href="{{ route('market.index', ['q' => 'Arco']) }}" class="badge text-bg-secondary text-decoration-none">Arcos</a>
                <a href="{{ route('market.index', ['q' => 'Oridecon']) }}" class="badge text-bg-secondary text-decoration-none">Minérios</a>
                <a href="{{ route('market.index', ['q' => 'Asa de Mosca']) }}" class="badge text-bg-secondary text-decoration-none">Teleportes</a>
            </div>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">
            @if(!empty($search))
                Resultados para: <span class="text-warning">"{{ $search }}"</span>
                <small class="text-secondary fs-6">({{ count($results) }} encontrados)</small>
            @else
                Itens Populares & Essenciais da Seed
                <small class="text-secondary fs-6">({{ count($results) }} exibidos)</small>
            @endif
        </h4>
        @if(!empty($search))
            <a href="{{ route('market.index') }}" class="btn btn-sm btn-outline-secondary">Limpar Busca</a>
        @endif
    </div>

    @if(empty($results))
        <div class="alert alert-secondary text-center py-5">
            <h5 class="mb-2">Nenhum item encontrado</h5>
            <p class="mb-0 text-secondary">Tente buscar por um termo mais amplo ou pelo ID numérico do item.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-lg-2 g-3">
            @foreach($results as $item)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <div class="d-flex align-items-center gap-2">
                                <img
                                    src="{{ $item['sprite_url'] }}"
                                    alt="{{ $item['name'] }}"
                                    width="28"
                                    height="28"
                                    style="image-rendering: pixelated;"
                                    onerror="this.src='https://static.divine-pride.net/images/items/item/501.png'"
                                >
                                <span class="fw-bold fs-6" style="color: #002b36;">{{ $item['name'] }}</span>
                            </div>
                            <div>
                                <span class="badge text-bg-secondary font-monospace">ID {{ $item['id'] }}</span>
                                <span class="badge text-bg-info">{{ $item['type'] }}</span>
                            </div>
                        </div>

                        <div class="card-body py-3">
                            <div class="row text-secondary small mb-2">
                                <div class="col-6">
                                    <span>Aegis:</span> <code style="color: #268bd2;">{{ $item['aegis'] }}</code>
                                </div>
                                <div class="col-3">
                                    <span>Peso:</span> <strong style="color: #073642;">{{ $item['weight'] }}</strong>
                                </div>
                                <div class="col-3 text-end">
                                    <span>Preço:</span> <strong class="text-warning">{{ number_format($item['buy']) }}z</strong>
                                </div>
                            </div>

                            @if(!empty($item['script']))
                                <div class="p-2 mb-2 rounded font-monospace small text-break border" style="background-color: #f4edd9; color: #859900;">
                                    {{ $item['script'] }}
                                </div>
                            @endif

                            {{-- Lojas de Jogadores (Vendings) --}}
                            <div class="mt-2 pt-2 border-top">
                                <h6 class="fw-bold small mb-1 d-flex justify-content-between align-items-center" style="color: #268bd2;">
                                    <span>Mercadores Jogadores (Vending)</span>
                                    <span class="badge text-bg-secondary">{{ count($item['player_vendings']) }} lojas</span>
                                </h6>
                                @if(count($item['player_vendings']) > 0)
                                    <div class="list-group list-group-flush small">
                                        @foreach($item['player_vendings'] as $vendor)
                                            <div class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold" style="color: #002b36;">{{ $vendor['merchant_name'] }}</span>
                                                    <small class="text-secondary">({{ $vendor['map'] }} {{ $vendor['x'] }},{{ $vendor['y'] }})</small>
                                                    @if($vendor['refine'] > 0)
                                                        <span class="badge text-bg-warning">+{{ $vendor['refine'] }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge text-bg-secondary">{{ $vendor['amount'] }}x</span>
                                                    <strong class="text-warning font-monospace">{{ number_format($vendor['price']) }}z</strong>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-secondary small mb-1 fst-italic">Nenhum jogador vendendo este item no momento.</p>
                                @endif
                            </div>

                            {{-- Lojas de NPCs --}}
                            @if(count($item['npc_shops']) > 0)
                                <div class="mt-2 pt-2 border-top">
                                    <h6 class="fw-bold small mb-1" style="color: #b58900;">Lojas de NPCs da Seed</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($item['npc_shops'], 0, 4) as $shop)
                                            <span class="badge text-bg-secondary font-monospace">
                                                {{ $shop['shop_name'] }} ({{ $shop['map'] }}): <span class="text-warning">{{ number_format($shop['price']) }}z</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Monstros que Dropam (Drops Procedurais) --}}
                            @if(count($item['dropped_by']) > 0)
                                <div class="mt-2 pt-2 border-top">
                                    <h6 class="fw-bold small mb-1" style="color: #859900;">
                                        Drop Procedural da Seed ({{ count($item['dropped_by']) }} monstros)
                                    </h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($item['dropped_by'], 0, 8) as $dropMob)
                                            <a href="{{ route('mobdb.show', $dropMob['mob_id']) }}"
                                               class="badge text-bg-secondary text-decoration-none d-inline-flex align-items-center gap-1"
                                               style="transition: all .15s ease;"
                                               title="Ver ficha do monstro {{ $dropMob['mob_name'] }} na Enciclopédia">
                                                <img src="{{ $dropMob['icon_url'] ?? ('https://static.divine-pride.net/images/mobs/png/' . $dropMob['mob_id'] . '.png') }}" width="16" height="16" alt="" style="image-rendering: pixelated; object-fit: contain;" onerror="if(!this.dataset.fallback){this.dataset.fallback='1';this.src='{{ $dropMob['sprite_url'] }}';}else{this.style.display='none';}">
                                                <span style="color: #002b36;">{{ $dropMob['mob_name'] }}</span>
                                                <span class="text-success font-monospace">({{ $dropMob['rate_percent'] }}%)</span>
                                            </a>
                                        @endforeach
                                        @if(count($item['dropped_by']) > 8)
                                            <span class="badge text-bg-secondary">+{{ count($item['dropped_by']) - 8 }} outros</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer py-2 text-end">
                            <a href="{{ $item['divine_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info">
                                Ficha no Divine Pride
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<footer class="text-secondary py-4 text-center mt-5">
    <div class="container">
        <p class="mb-1" style="color: #073642; font-weight: 700;">RagnaRogue — Servidor Procedural Roguelike</p>
        <small class="text-secondary">Dados extraídos deterministicamente da Seed <code>{{ $activeSeed }}</code></small>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.querySelector('form[action="{{ route('market.index') }}"]');
    if (!searchForm) return;

    const searchInput = searchForm.querySelector('input[name="q"]');
    if (!searchInput) return;

    let debounceTimer = null;

    // Focar e colocar o cursor no final do texto ao carregar
    if (searchInput.value) {
        searchInput.focus();
        const len = searchInput.value.length;
        searchInput.setSelectionRange(len, len);
    }

    // Busca reativa com debounce de 450ms
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            searchForm.submit();
        }, 450);
    });
});
</script>

</body>
</html>
