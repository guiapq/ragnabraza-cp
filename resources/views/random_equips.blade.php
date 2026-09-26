<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Equipamentos Randomizados — {{ config('app.name', 'RagnaRogue') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <!-- Styles & Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">
    <style>
        .mystery-title {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 2px;
            color: #d33682;
            font-weight: 800;
        }
        .mystery-card {
            transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            border-left: 4px solid #b58900;
        }
        .mystery-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .comic-card {
            border-left: 4px solid #dc322f !important;
            background: linear-gradient(to right, #fff9f9, #ffffff);
        }
    </style>
</head>
<body class="antialiased">
<x-banner/>
<x-navigation-menu/>

<div class="container py-4">
    {{-- Header / Hero da Página --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 shadow-sm hero-solarized" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border: 1px solid var(--sol-border);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge text-bg-warning font-monospace text-uppercase">Seed Ativa: {{ $activeSeed }}</span>
                    <span class="badge text-bg-danger font-monospace text-uppercase">Pre-Renewal Roguelike</span>
                </div>
                <h1 class="display-6 fw-bold mb-2" style="color: #002b36;">Equipamentos Randomizados</h1>
                <p class="lead mb-0 fs-6" style="color: #586e75;">
                    Conheça a arquitetura procedural de itens do mundo: distribuição em Curva de Bell, limites rigorosos de Pre-Renewal, papéis dedicados por slot de armadura e os raros itens cômicos de tiers altos.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-warning fw-bold px-4 py-2 shadow-sm">
                    Jogar no roBrowser
                </a>
            </div>
        </div>
    </div>

    {{-- Explicação da Curva de Bell e Regras de Pre-RE --}}
    <div class="row g-4 mb-4">
        {{-- Card Curva de Bell --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                        <span>🔔</span> A Curva de Bell dos Afixos (Gaussiana)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-secondary small mb-3">
                        A utilidade das armas e equipamentos não é linear nem ingênua; ela obedece a uma distribuição normal determinística baseada na seed:
                    </p>
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="badge text-bg-secondary me-2">Tier 0 (15%)</span>
                                <strong>Ruim / Defeituosa</strong>: Pequenas falhas de forja, penalidades leves de precisão ou dano.
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="badge text-bg-primary me-2">Tier 1 (50%)</span>
                                <strong>Medíocre / Comum</strong>: O centro da curva; atributos sólidos e honestos para o dia a dia.
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="badge text-bg-warning me-2">Tier 2 (25%)</span>
                                <strong>Boa / Rara</strong>: Bônus expressivos, velocidade de ataque e autocasts moderados.
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="badge text-bg-danger me-2">Tier 3 (10%)</span>
                                <strong>Ótima / Primorosa</strong>: O topo da curva; atributos de ponta e os infames wildcards cômicos.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Regras Pre-RE --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                        <span>🛡️</span> Regras Mecânicas Pre-Renewal
                    </h5>
                </div>
                <div class="card-body p-4">
                    <ul class="text-secondary small mb-0 ps-3">
                        <li class="mb-2"><strong>DEF em Armas:</strong> Como a Hard DEF no Pre-RE reduz dano em porcentagem direta, armas <strong>não recebem DEF</strong> (limite máximo de 5).</li>
                        <li class="mb-2"><strong>Teto de DEFM:</strong> Limitado estritamente a <strong>no máximo 15</strong> em equipamentos regulares.</li>
                        <li class="mb-2"><strong>Reduções Estratégicas:</strong> Reduções elementais de até <strong>20%</strong> e redução de dano Neutro calibrada até <strong>12%</strong>.</li>
                        <li class="mb-2"><strong>Papel por Slot:</strong> Armaduras dão pontos defensivos; Sapatos dão sobrevivência e ATQ; Capas dão Esquiva e redução; Acessórios dão skills e status brutos até +5.</li>
                        <li class="mb-2"><strong>All Stats:</strong> Limitado a <strong>no máximo +3</strong> em equipamentos normais.</li>
                        <li><strong>Propriedade Elemental:</strong> Exatamente <strong>20% das armas</strong> adquirem um elemento natural fixo.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Amostras de Equipamentos Randomizados do Mundo Atual --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h4 fw-bold mb-1" style="color: #002b36;">Amostras de Equipamentos do Mundo</h2>
            <p class="text-muted small mb-0">
                Abaixo estão exemplos reais gerados pela seed atual. Para preservar o mistério roguelike, a identidade base de cada item é oculta como <code class="mystery-title">????????????</code>.
            </p>
        </div>
    </div>

    @foreach($categories as $catKey => $cat)
        @if(!empty($cat['items']))
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center py-3 bg-light">
                    <div>
                        <h5 class="h6 mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <span>{{ $cat['title'] }}</span>
                        </h5>
                        <small class="text-muted">{{ $cat['description'] }}</small>
                    </div>
                    <span class="badge {{ $cat['badge_color'] }} font-monospace">
                        {{ $cat['badge'] }}
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @foreach($cat['items'] as $item)
                            <div class="col-md-6 col-12">
                                <div class="card p-3 h-100 mystery-card {{ $catKey === 'comic_weapons' || $catKey === 'comic_armors' ? 'comic-card' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="mystery-title fs-5 d-block">
                                                {{ $item['name'] }}
                                            </span>
                                            <span class="badge bg-light text-dark border font-monospace small">
                                                {{ $item['slot'] }}
                                            </span>
                                            @if($item['elv'] > 0)
                                                <span class="badge bg-secondary-subtle text-secondary small">
                                                    Nv. Mínimo: {{ $item['elv'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="badge text-bg-warning font-monospace">
                                            Identificado
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        @foreach($item['bonuses'] as $bonus)
                                            <span class="badge text-bg-light border text-start fw-normal font-monospace py-1 px-2 text-wrap">
                                                {{ $bonus }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</div>

</body>
</html>
