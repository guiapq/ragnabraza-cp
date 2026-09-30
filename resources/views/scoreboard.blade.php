<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="15">

    <title>Ato 1: a lenda do cometa — Placar da Run | {{ config('app.name', 'RagnaRogue') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- Styles & Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">
    <style>
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .hero-cosmic {
            background: linear-gradient(135deg, #002b36 0%, #073642 50%, #001f27 100%);
            border: 1px solid rgba(181, 137, 0, 0.4);
            color: #fdf6e3;
        }
        .fragment-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            border: 1px solid var(--sol-border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .fragment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        .fragment-defeated {
            background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
            border-color: #38a169;
        }
        .fragment-active {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border-color: #e53e3e;
        }
        .badge-gold {
            background-color: #b58900;
            color: #ffffff;
        }
        .badge-cosmic {
            background-color: #6c71c4;
            color: #ffffff;
        }
    </style>
</head>
<body class="antialiased" style="background-color: #fdf6e3;">
<x-banner/>
<x-navigation-menu/>

<div class="container py-4">
    {{-- Header da Run Roguelike --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 shadow-lg hero-cosmic position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    @if($runMeta && $runMeta->is_finished)
                        <span class="badge text-bg-success px-3 py-2 fs-6 font-mono text-uppercase shadow-sm">
                            👑 Run Concluída — Era da Luz
                        </span>
                        <span class="badge bg-warning text-dark font-mono px-3 py-2 fs-6">
                            🌟 Salvador: {{ $runMeta->winning_killer }}
                        </span>
                    @else
                        <span class="badge text-bg-danger px-3 py-2 fs-6 font-mono text-uppercase shadow-sm">
                            ⚡ Run em Andamento
                        </span>
                        <span class="badge bg-primary font-mono px-3 py-2 fs-6">
                            Névoa Ativa
                        </span>
                    @endif
                    <span class="badge badge-gold font-mono px-3 py-2 fs-6">Seed: {{ $seed }}</span>
                    <small class="text-white-50 font-mono ms-auto ms-lg-0">Auto-refresh 15s</small>
                </div>
                <h1 class="display-6 fw-black mb-2 text-white">
                    Ato 1: a lenda do cometa
                </h1>
                <p class="lead mb-3 fs-6" style="color: #93a1a1;">
                    Acompanhamento em tempo real da reconquista de Rune-Midgard. Destrua os 7 Fragmentos com Relíquias Sagradas (Armas Tier 4) e conquiste o maior score de metas!
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-dark border border-secondary font-mono">@placar</span>
                    <span class="badge text-bg-dark border border-secondary font-mono">@radar</span>
                    <span class="badge text-bg-dark border border-secondary font-mono">@cometa</span>
                    <span class="badge text-bg-dark border border-secondary font-mono">@pray</span>
                    <span class="badge text-bg-dark border border-secondary font-mono">@sos</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="p-3 rounded-3" style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="small text-uppercase font-mono mb-1 text-warning fw-bold">Progresso dos Fragmentos</div>
                    <div class="display-5 fw-bold mb-2 text-white font-mono">
                        {{ $fragmentsDestroyedCount }} <span class="fs-4 text-white-50">/ 7</span>
                    </div>
                    <div class="progress mb-2" style="height: 10px; background-color: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" 
                             style="width: {{ ($fragmentsDestroyedCount / 7) * 100 }}%"></div>
                    </div>
                    <small class="text-white-50 d-block mb-3">
                        {{ 7 - $fragmentsDestroyedCount > 0 ? (7 - $fragmentsDestroyedCount) . ' fontes de névoa restantes' : 'Todas as fontes purificadas!' }}
                    </small>
                    <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-warning fw-bold w-100 py-2 shadow">
                        Jogar Agora no roBrowser
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards dos 7 Fragmentos do Cometa --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="h5 fw-bold mb-0" style="color: #002b36;">Status dos 7 Fragmentos do Cometa Negro</h3>
                <small class="text-secondary">Monstros que consumiram as pedras estrelares. Exigem Arma Tier 4 para destruição permanente.</small>
            </div>
        </div>

        <div class="row g-3">
            @forelse($fragments as $frag)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="fragment-card p-3 h-100 {{ $frag->defeated ? 'fragment-defeated' : 'fragment-active' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge {{ $frag->defeated ? 'text-bg-success' : 'text-bg-danger' }} font-mono text-uppercase">
                                {{ $frag->defeated ? '✓ Destruído' : '⚠️ Ativo' }}
                            </span>
                            <span class="badge text-bg-dark font-mono">#{{ $frag->id }}</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: #002b36;">{{ $frag->title }}</h6>
                        <div class="text-danger fw-bold fs-6 mb-2">{{ $frag->mob_name }}</div>
                        <div class="small text-secondary mb-2">
                            <strong>Mapa:</strong> <code class="text-dark">{{ $frag->map_name }}</code>
                        </div>
                        @if($frag->defeated)
                            <div class="small p-2 rounded bg-white border border-success text-success font-mono">
                                <strong>Derrotado por:</strong><br>{{ $frag->defeated_by }}
                            </div>
                        @else
                            <div class="small p-2 rounded bg-white border border-danger text-danger">
                                <em>Emanando névoa corrosiva...</em>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info font-mono">
                        Nenhum fragmento registrado para a seed atual.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- GRANDE PLACAR POR GOALS DA RUN --}}
    <div class="card shadow-sm mb-5" style="border: 1px solid var(--sol-border);">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #eee8d5;">
            <div>
                <h4 class="h5 mb-0 fw-bold" style="color: #002b36;">🏆 Grande Placar por Goals (Metas Roguelike)</h4>
                <small class="text-secondary">Classificação geral calculada por Fragmentos, Mapas Purificados, MVPs, Abates PvP, Mobs e Nível 99</small>
            </div>
            <span class="badge text-bg-warning font-mono fs-6">
                {{ $leaderboard->count() }} Participantes Registrados
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light font-mono small text-uppercase" style="border-bottom: 2px solid var(--sol-border);">
                    <tr>
                        <th class="text-center" style="width: 70px;">Rank</th>
                        <th>Herói & Conquistas</th>
                        <th class="text-center">Score Total</th>
                        <th class="text-center">Fragmentos</th>
                        <th class="text-center">Mapas Limpos</th>
                        <th class="text-center">MVPs</th>
                        <th class="text-center">PvP</th>
                        <th class="text-center">Mobs</th>
                        <th class="text-center">Mortes</th>
                        <th class="text-center">Tempo 99</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard as $idx => $entry)
                        <tr class="{{ $idx === 0 ? 'table-warning' : '' }}">
                            <td class="text-center font-mono fw-bold fs-5" style="color: {{ $idx === 0 ? '#b58900' : ($idx === 1 ? '#268bd2' : ($idx === 2 ? '#cb4b16' : '#657b83')) }};">
                                {{ $idx === 0 ? '🥇' : ($idx === 1 ? '🥈' : ($idx === 2 ? '🥉' : '#' . ($idx + 1))) }}
                            </td>
                            <td>
                                <div class="fw-bold fs-6" style="color: #002b36;">
                                    {{ $entry->char_name }}
                                    @if($entry->delivered_final_blow)
                                        <span class="badge bg-warning text-dark font-mono ms-1">🌟 Salvador</span>
                                    @endif
                                </div>
                                <div class="small text-secondary font-mono">
                                    {{ $entry->medals ?: '[Aventureiro]' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fs-5 fw-bold font-mono text-primary">
                                    {{ number_format($entry->total_score) }}
                                </div>
                                <small class="text-secondary font-mono" style="font-size: 10px;">PONTOS</small>
                            </td>
                            <td class="text-center font-mono fw-bold text-success">
                                {{ $entry->fragments_destroyed }}x
                            </td>
                            <td class="text-center font-mono fw-bold" style="color: #2aa198;">
                                {{ $entry->maps_conquered }}x
                            </td>
                            <td class="text-center font-mono fw-bold" style="color: #b58900;">
                                {{ $entry->mvp_kills }}x
                            </td>
                            <td class="text-center font-mono fw-bold" style="color: #d33682;">
                                {{ $entry->pvp_kills }}x
                            </td>
                            <td class="text-center font-mono small text-secondary">
                                {{ number_format($entry->mob_kills) }}
                            </td>
                            <td class="text-center font-mono {{ $entry->deaths > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $entry->deaths }}
                            </td>
                            <td class="text-center font-mono text-secondary small">
                                {{ $entry->level_99_time > 0 ? floor($entry->level_99_time / 60) . 'm' : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-secondary">
                                <p class="mb-1 fw-bold">Nenhum aventureiro pontuou nesta seed ainda.</p>
                                <small>Conquiste mapas, derrote monstros e destrua fragmentos para subir no placar!</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer py-2 text-secondary small d-flex flex-wrap justify-content-between" style="background-color: #eee8d5;">
            <span>Fórmula: (Fragmentos × 5.000) + (Final × 15.000) + (Mapas × 500) + (MVPs × 1.000) + (PvP × 300) + Mobs + Bônus 99 - (Mortes × 200)</span>
            <span>Comando no jogo: <code>@placar</code></span>
        </div>
    </div>

    {{-- Grid Secundário: Speedrun 99 e Caçadores de MVP Tradicionais --}}
    <div class="row g-4">
        {{-- Coluna 1: Speedrun 1-99 --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="h5 mb-0 fw-bold" style="color: #002b36;">⚡ Speedrun 1-99</h4>
                        <small class="text-secondary">Menor tempo acumulado até atingir nível máximo</small>
                    </div>
                    <span class="badge text-bg-success font-monospace">
                        {{ $speedruns->count() }} Finalistas
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        @forelse($speedruns as $idx => $run)
                            <div class="list-group-item px-2 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="font-mono font-bold fs-5 text-center" style="width: 35px; color: {{ $idx === 0 ? '#b58900' : ($idx === 1 ? '#268bd2' : ($idx === 2 ? '#cb4b16' : '#657b83')) }};">
                                        {{ $idx + 1 }}º
                                    </span>
                                    <div>
                                        <div class="fw-bold fs-6" style="color: #002b36;">{{ $run->name }}</div>
                                        <small class="text-secondary">
                                            {{ $run->class_name ?? "Classe {$run->class}" }} • Concluído às {{ $run->achieved_at?->format('H:i:s') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="font-monospace fw-bold fs-5 text-success">
                                        {{ $run->formatted_time }}
                                    </div>
                                    <small class="text-secondary text-uppercase" style="font-size: 10px;">Tempo Total</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-secondary">
                                <p class="mb-1 fw-bold">Nenhum jogador alcançou o nível 99 nesta seed ainda.</p>
                                <small>A corrida está em andamento nos mapas do servidor.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna 2: MVP Bounty Hunters --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="h5 mb-0 fw-bold" style="color: #002b36;">👑 Caçadores de MVP (Bounty)</h4>
                        <small class="text-secondary">Ranking por total de abates de chefes</small>
                    </div>
                    <span class="badge text-bg-danger font-monospace">
                        Top Caçadores
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        @forelse($mvpBounties as $idx => $hunter)
                            <div class="list-group-item px-2 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="font-mono font-bold fs-5 text-center" style="width: 35px; color: {{ $idx === 0 ? '#dc322f' : '#657b83' }};">
                                        {{ $idx + 1 }}º
                                    </span>
                                    <div>
                                        <div class="fw-bold fs-6" style="color: #002b36;">{{ $hunter->char_name }}</div>
                                        <small class="text-secondary">
                                            {{ $hunter->distinct_mvps }} chefes distintos eliminados
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="font-monospace fw-bold fs-5 text-danger">
                                        {{ $hunter->total_kills }}x
                                    </div>
                                    <small class="text-secondary text-uppercase" style="font-size: 10px;">Total Abates</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-secondary">
                                <p class="mb-1 fw-bold">Nenhum MVP derrotado nesta seed ainda.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Feed de Abates Recentes --}}
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-secondary small fw-bold text-uppercase mb-2">Últimos Abates Registrados:</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @forelse($recentKills as $k)
                                <span class="badge text-bg-secondary font-monospace p-2">
                                    <strong class="text-danger">{{ $k->char_name }}</strong> derrotou <span class="text-warning">{{ $k->mob_name }}</span>
                                </span>
                            @empty
                                <small class="text-secondary italic">Aguardando primeiro registro de abate...</small>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="py-4 text-center text-secondary small border-top mt-5" style="background-color: #eee8d5;">
    <div class="container">
        <p class="mb-0">RagnaRogue Roguelike v3 — Servidor Privado & Topologia Procedural de Mapas.</p>
    </div>
</footer>

</body>
</html>
