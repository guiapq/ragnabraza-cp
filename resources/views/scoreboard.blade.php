<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="15">

    <title>Placar ao Vivo & Torneio — {{ config('app.name', 'RagnaRogue') }}</title>

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
    {{-- Header do Torneio --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 shadow-sm hero-solarized" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border: 1px solid var(--sol-border);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge text-bg-danger font-monospace text-uppercase">Ao Vivo no Evento</span>
                    <span class="badge text-bg-warning font-monospace text-uppercase">Seed: {{ $seed }}</span>
                    <small class="text-secondary font-monospace">Auto-refresh 15s</small>
                </div>
                <h1 class="display-6 fw-bold mb-2" style="color: #002b36;">
                    Torneio Roguelike — Sprint 12 Horas
                </h1>
                <p class="lead mb-0 fs-6" style="color: #586e75;">
                    Acompanhamento em tempo real da corrida para Base 99 e caça de MVPs da temporada atual.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-warning fw-bold px-4 py-2 shadow-sm">
                    Jogar no roBrowser
                </a>
            </div>
        </div>
    </div>

    {{-- Grid das duas arenas: Speedrun e MVP Bounty --}}
    <div class="row g-4">
        {{-- Coluna 1: Speedrun 1-99 --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="h5 mb-0 fw-bold" style="color: #002b36;">Speedrun 1-99</h4>
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
                            <div class="text-center py-5 text-secondary">
                                <p class="mb-1 fw-bold">Nenhum jogador alcançou o nível 99 nesta seed ainda.</p>
                                <small>A corrida está em andamento nos mapas do servidor.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer py-2 text-secondary small d-flex justify-content-between">
                    <span>Critério: Tempo total registrado</span>
                    <span>Classificação oficial da seed</span>
                </div>
            </div>
        </div>

        {{-- Coluna 2: MVP Bounty Hunters --}}
        <div class="col-lg-6 col-12">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="h5 mb-0 fw-bold" style="color: #002b36;">Caçadores de MVP (Bounty)</h4>
                        <small class="text-secondary">Pontuação por abates de chefes procedurais</small>
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
                            <div class="text-center py-5 text-secondary">
                                <p class="mb-1 fw-bold">Nenhum MVP foi derrotado nesta seed ainda.</p>
                                <small>Os chefes continuam patrulhando seus mapas.</small>
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
                <div class="card-footer py-2 text-secondary small d-flex justify-content-between">
                    <span>Premiação concedida ao fim da rodada</span>
                    <span>Atualização contínua</span>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-secondary py-4 text-center mt-5">
    <div class="container">
        <p class="mb-1" style="color: #073642; font-weight: 700;">RagnaRogue — Servidor Procedural Roguelike & roBrowser</p>
        <small class="text-secondary">Placar de Torneio vinculado à Seed <code>{{ $seed }}</code></small>
    </div>
</footer>

</body>
</html>
