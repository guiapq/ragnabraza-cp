<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $mob['name'] }} (#{{ $mob['id'] }}) — Enciclopédia — {{ config('app.name', 'RagnaRogue') }}</title>
    <meta name="description" content="{{ $mob['name'] }}: Lv {{ $mob['level'] }}, {{ $mob['race'] }}, {{ $mob['element'] }}. HP: {{ number_format($mob['hp']) }}. {{ count($mob['drops']) }} drops.">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">

    <style>
        .stat-card {
            background: var(--sol-base2);
            border: 1px solid var(--sol-border);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            text-align: center;
        }
        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--sol-base1);
            font-weight: 700;
        }
        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--sol-base01);
        }
        .drop-bar {
            height: 6px;
            border-radius: 3px;
            background: var(--sol-border);
            overflow: hidden;
        }
        .drop-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: var(--sol-green);
            transition: width .4s ease;
        }
        .drop-row:hover {
            background: rgba(38,139,210,.06);
        }
        .mob-sprite-lg {
            image-rendering: pixelated;
            max-height: 160px;
            filter: drop-shadow(0 4px 8px rgba(0,43,54,.2));
        }
        .map-badge {
            background: var(--sol-base3);
            border: 1px solid var(--sol-border);
            color: var(--sol-base01);
            font-family: monospace;
            font-size: .78rem;
            padding: 3px 8px;
            border-radius: 4px;
            white-space: nowrap;
        }
        .section-title {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--sol-base1);
            border-bottom: 2px solid var(--sol-border);
            padding-bottom: .4rem;
            margin-bottom: 1rem;
        }
        .element-tag { background: var(--sol-cyan);   color:#fff; }
        .race-tag    { background: var(--sol-violet); color:#fff; }
        .lv-tag      { background: var(--sol-yellow); color:#fff; }
        .hp-tag      { background: var(--sol-red);    color:#fff; }
    </style>
</head>
<body class="antialiased">
<x-banner/>
<x-navigation-menu/>

<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--sol-blue);">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mobdb.index') }}" style="color:var(--sol-blue);">Monstros</a></li>
            <li class="breadcrumb-item active" style="color:var(--sol-base1);">{{ $mob['name'] }}</li>
        </ol>
    </nav>

    {{-- Hero do Monstro --}}
    <div class="card shadow mb-4" style="border-radius:.75rem;overflow:hidden;">
        <div class="card-body p-0">
            <div class="row g-0">
                {{-- Sprite + identidade --}}
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center p-4"
                     style="background:linear-gradient(135deg,#fdf6e3,#eee8d5);border-right:1px solid var(--sol-border);">
                    <img
                        src="{{ $mob['sprite_url'] }}"
                        alt="{{ $mob['name'] }}"
                        class="mob-sprite-lg mb-3"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                    <div style="display:none;font-size:4rem;color:var(--sol-base1);">?</div>

                    <h1 class="fw-bold text-center mb-1" style="font-size:1.4rem;color:var(--sol-base02);">
                        {{ $mob['name'] }}
                    </h1>
                    <div class="text-muted small mb-3" style="color:var(--sol-base1)!important;">
                        ID #{{ $mob['id'] }}
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                        <span class="badge lv-tag">Lv {{ $mob['level'] }}</span>
                        <span class="badge race-tag">{{ $mob['race'] }}</span>
                        <span class="badge element-tag">{{ $mob['element'] }}</span>
                    </div>

                    <a href="{{ $mob['divine_url'] }}" target="_blank"
                       class="btn btn-sm btn-outline-secondary mt-2"
                       style="font-size:.75rem;">
                        Divine Pride
                    </a>
                </div>

                {{-- Stats --}}
                <div class="col-md-8 p-4">
                    <p class="section-title">Atributos</p>
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">HP</div>
                                <div class="stat-value" style="color:var(--sol-red);">{{ number_format($mob['hp']) }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">ATK</div>
                                <div class="stat-value">{{ $mob['atk'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">DEF</div>
                                <div class="stat-value">{{ $mob['def'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">MDEF</div>
                                <div class="stat-value">{{ $mob['mdef'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">Base EXP</div>
                                <div class="stat-value" style="color:var(--sol-blue);">{{ number_format($mob['exp']) }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">Job EXP</div>
                                <div class="stat-value" style="color:var(--sol-violet);">{{ number_format($mob['jexp']) }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">Raca</div>
                                <div class="stat-value" style="font-size:1rem;">{{ $mob['race'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="stat-card">
                                <div class="stat-label">Elemento</div>
                                <div class="stat-value" style="font-size:1rem;">{{ $mob['element'] }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Mapas de Spawn --}}
                    <p class="section-title">Mapas de Spawn ({{ count($maps) }})</p>
                    @if(count($maps) > 0)
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($maps as $map)
                                <span class="map-badge">{{ $map }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small">Nenhum spawn registrado para esta seed (mob de instancia ou especial).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Drops --}}
    @if(count($mob['drops']) > 0)
        <div class="card shadow mb-4" style="border-radius:.75rem;">
            <div class="card-body p-4">
                <p class="section-title">Drops ({{ count($mob['drops']) }} itens)</p>

                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.9rem;">
                        <thead>
                            <tr style="color:var(--sol-base1);font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;">
                                <th style="width:48px;"></th>
                                <th>Item</th>
                                <th class="text-end" style="width:100px;">Chance</th>
                                <th style="width:200px;"></th>
                                <th class="text-end" style="width:80px;">Links</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mob['drops'] as $drop)
                                @php
                                    $pct = $drop['rate_percent'];
                                    $barWidth = min(100, $pct);
                                    $barColor = $pct >= 50 ? 'var(--sol-green)' : ($pct >= 10 ? 'var(--sol-cyan)' : ($pct >= 1 ? 'var(--sol-blue)' : 'var(--sol-violet)'));
                                @endphp
                                <tr class="drop-row">
                                    <td class="align-middle text-center">
                                        <img src="{{ $drop['sprite_url'] }}"
                                             alt="{{ $drop['item_name'] }}"
                                             style="width:28px;height:28px;object-fit:contain;image-rendering:pixelated;"
                                             onerror="this.style.opacity='.3'">
                                    </td>
                                    <td class="align-middle fw-semibold" style="color:var(--sol-base01);">
                                        {{ $drop['item_name'] }}
                                        <span class="text-muted ms-1" style="font-size:.75rem;font-weight:400;">#{{ $drop['item_id'] }}</span>
                                    </td>
                                    <td class="align-middle text-end fw-bold" style="color:{{ $barColor }};font-family:monospace;">
                                        {{ $pct }}%
                                    </td>
                                    <td class="align-middle px-3">
                                        <div class="drop-bar">
                                            <div class="drop-bar-fill" style="width:{{ $barWidth }}%;background:{{ $barColor }};"></div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('market.item', $drop['item_id']) }}"
                                           class="btn btn-outline-secondary btn-sm"
                                           style="font-size:.7rem;padding:2px 8px;">
                                            Item
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 p-3 rounded-2" style="background:var(--sol-base3);border:1px solid var(--sol-border);font-size:.78rem;color:var(--sol-base1);">
                    Taxas calculadas sobre a tabela <code>mob_db.txt</code> da seed <strong>{{ $activeSeed }}</strong>.
                    Drop rates sao base/10000 (ex: 5500 = 55%).
                </div>
            </div>
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5" style="color:var(--sol-base1);">
                Este monstro nao possui drops registrados nesta seed.
            </div>
        </div>
    @endif

    {{-- Navegacao --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
        <a href="{{ route('mobdb.index') }}" class="btn btn-outline-secondary">
            Voltar para Enciclopédia
        </a>
        <div class="d-flex gap-2">
            @if($mob['id'] > 1001)
                <a href="{{ route('mobdb.show', $mob['id'] - 1) }}" class="btn btn-outline-secondary btn-sm">
                    #{{ $mob['id'] - 1 }}
                </a>
            @endif
            <a href="{{ route('mobdb.show', $mob['id'] + 1) }}" class="btn btn-outline-secondary btn-sm">
                #{{ $mob['id'] + 1 }}
            </a>
        </div>
    </div>

</div>

<footer class="py-4 mt-4" style="border-top:1px solid var(--sol-border);color:var(--sol-base1);">
    <div class="container text-center small">
        RagnaRogue &mdash; Seed <code>{{ $activeSeed }}</code>
    </div>
</footer>
</body>
</html>
