<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RagnaRogue') }} — Servidor Procedural Roguelike & Web Client</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Styles & Scripts (Vite) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">
</head>
<body class="antialiased">
<x-banner/>
<x-navigation-menu/>

<div id="banners">
    <x-landing.slider :seed="$seed ?? 'zawarudo'"/>
</div>

<div class="container py-3">
    <div class="row g-4">
        {{-- Coluna Principal: Notícias e Changelogs --}}
        <div class="col-lg-7 col-12">
            <x-landing.news-section :news="$news ?? []"/>
            <x-landing.changelog-section :changelogs="$changelogs ?? []"/>
        </div>

        {{-- Coluna Lateral: Status dos Serviços & Guia do roBrowser --}}
        <div class="col-lg-5 col-12">
            <x-landing.server-status-card
                :status="$serverStatus ?? []"
                :online-players="$generalStats['online'] ?? 0"
            />
            <x-landing.donate-card/>
        </div>

        {{-- Estatísticas Gerais do Mundo --}}
        <div class="col-12">
            <hr class="my-2">
            <x-landing.general-status :stats="$generalStats ?? []"/>
        </div>

        {{-- Mercado & Drops / Catálogo --}}
        <div class="col-lg-6 col-12">
            <x-landing.market-section :market-sample="$marketSample ?? []"/>
        </div>

        {{-- Rankings da Temporada --}}
        <div class="col-lg-6 col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h4 class="h5 mb-0 fw-bold">
                        Top Speedrunners & MVPs
                    </h4>
                    <a href="{{ route('event.scoreboard') }}" class="btn btn-sm btn-outline-warning">
                        Placar Completo →
                    </a>
                </div>
                <div class="card-body p-3">
                    <h6 class="text-warning small fw-bold mb-2">Speedrun Base 99</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 align-middle small">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Personagem</th>
                                    <th>Classe</th>
                                    <th class="text-end">Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($speedruns as $idx => $run)
                                    <tr>
                                        <td class="font-monospace text-warning">{{ $idx + 1 }}º</td>
                                        <td class="fw-bold" style="color: #002b36;">{{ $run->name }}</td>
                                        <td class="text-secondary">{{ $run->class_name ?? "Classe {$run->class}" }}</td>
                                        <td class="text-end font-monospace text-success fw-bold">{{ gmdate('H:i:s', $run->total_seconds) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-2 fst-italic">
                                            Nenhum speedrun completado nesta seed ainda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h6 class="text-danger small fw-bold mb-2">Caçadores de MVP</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 align-middle small">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Personagem</th>
                                    <th class="text-center">MVPs Únicos</th>
                                    <th class="text-end">Total Abates</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mvpBounties as $idx => $bounty)
                                    <tr>
                                        <td class="font-monospace text-danger">{{ $idx + 1 }}º</td>
                                        <td class="fw-bold" style="color: #002b36;">{{ $bounty->char_name }}</td>
                                        <td class="text-center font-monospace">{{ $bounty->distinct_mvps }}</td>
                                        <td class="text-end font-monospace text-danger fw-bold">{{ $bounty->total_kills }}x</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-2 fst-italic">
                                            Nenhum MVP abatido nesta seed ainda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-secondary py-4 text-center mt-5">
    <div class="container">
        <p class="mb-1" style="color: #073642; font-weight: 700;">RagnaRogue — Servidor Procedural Roguelike & roBrowser</p>
        <small>Desenvolvido com rAthena Pré-Renewal, Laravel Filament e cliente WebAssembly</small>
    </div>
</footer>

</body>
</html>
