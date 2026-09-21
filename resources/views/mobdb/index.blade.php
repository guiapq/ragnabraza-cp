<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enciclopédia de Monstros — {{ config('app.name', 'RagnaRogue') }}</title>
    <meta name="description" content="Database completo de monstros do mundo procedural da seed {{ $activeSeed }}. Busque por nome, raça ou elemento e veja drops, stats e mapas de spawn.">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/solarized-light.css') }}">

    <style>
        .mob-card {
            background: var(--sol-base2);
            border: 1px solid var(--sol-border);
            border-radius: 0.5rem;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .mob-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,43,54,.12);
            border-color: var(--sol-blue);
            color: inherit;
        }
        .mob-sprite {
            height: 72px;
            width: 100%;
            object-fit: contain;
            image-rendering: pixelated;
        }
        .mob-sprite-placeholder {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--sol-base1);
        }
        .filter-bar .form-control,
        .filter-bar .form-select {
            background-color: var(--sol-base3);
            border-color: var(--sol-border);
            color: var(--sol-base01);
        }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: var(--sol-blue);
            box-shadow: 0 0 0 0.2rem rgba(38,139,210,.15);
        }
        .badge-race   { background-color: var(--sol-violet); color: #fff; }
        .badge-elem   { background-color: var(--sol-cyan);   color: #fff; }
        .lv-badge     { background-color: var(--sol-yellow);  color: #fff; }
        .hero-banner  { background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border: 1px solid var(--sol-border); }
        .page-link    { color: var(--sol-blue); background-color: var(--sol-base3); border-color: var(--sol-border); }
        .page-item.active .page-link { background-color: var(--sol-blue); border-color: var(--sol-blue); color: #fff; }
        .page-link:hover  { background-color: var(--sol-base2); color: var(--sol-blue); }
    </style>
</head>
<body class="antialiased">
<x-banner/>
<x-navigation-menu/>

<div class="container py-4">

    {{-- Hero --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 shadow-sm hero-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge font-monospace text-uppercase mb-2" style="background:var(--sol-yellow);color:#fff;">Seed: {{ $activeSeed }}</span>
                <h1 class="display-6 fw-bold mb-2" style="color:var(--sol-base03);">Enciclopédia de Monstros</h1>
                <p class="lead mb-0 fs-6" style="color:var(--sol-base01);">
                    {{ number_format($total) }} monstros com stats, drops e mapas de spawn gerados pela seed procedural.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('market.index') }}" class="btn btn-outline-secondary me-2">Mercado</a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Inicio</a>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('mobdb.index') }}" class="filter-bar mb-4">
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text" style="background:var(--sol-base2);border-color:var(--sol-border);">Nome / ID</span>
                            <input type="text" name="q" value="{{ $search }}"
                                   placeholder="ex: Poring, 1002, Scorpion..."
                                   class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="race" class="form-select form-select-lg">
                            <option value="">Qualquer raca</option>
                            @foreach($races as $raceId => $raceName)
                                <option value="{{ $raceName }}" @selected($race === $raceName)>{{ $raceName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="element" class="form-select form-select-lg">
                            <option value="">Qualquer elemento</option>
                            @foreach($elements as $elemId => $elemName)
                                <option value="{{ $elemName }}" @selected($element === $elemName)>{{ $elemName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select form-select-lg">
                            <option value="id"    @selected($sort==='id')>ID</option>
                            <option value="name"  @selected($sort==='name')>Nome</option>
                            <option value="level" @selected($sort==='level')>Nivel</option>
                            <option value="hp"    @selected($sort==='hp')>HP</option>
                            <option value="exp"   @selected($sort==='exp')>EXP (maior)</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="background:var(--sol-blue);border-color:var(--sol-blue);">
                            Buscar
                        </button>
                    </div>
                </div>
                @if($search || $race || $element)
                    <div class="mt-2">
                        <a href="{{ route('mobdb.index') }}" class="btn btn-sm btn-outline-secondary">Limpar filtros</a>
                        <span class="text-muted ms-2 small">{{ number_format($total) }} resultados</span>
                    </div>
                @endif
            </div>
        </div>
    </form>

    {{-- Grid de monstros --}}
    @if(count($mobs) > 0)
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mb-4">
            @foreach($mobs as $mob)
                <div class="col">
                    <a href="{{ route('mobdb.show', $mob['id']) }}" class="mob-card p-2">
                        <div class="text-center mb-1">
                            <img src="{{ $mob['sprite_url'] }}"
                                 alt="{{ $mob['name'] }}"
                                 class="mob-sprite"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div class="mob-sprite-placeholder" style="display:none;">?</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-semibold small text-truncate" style="color:var(--sol-base01);font-size:.78rem;" title="{{ $mob['name'] }}">
                                {{ $mob['name'] }}
                            </div>
                            <div class="d-flex justify-content-center gap-1 mt-1 flex-wrap">
                                <span class="badge lv-badge" style="font-size:.65rem;">Lv {{ $mob['level'] }}</span>
                                <span class="badge badge-race"  style="font-size:.65rem;">{{ $mob['race'] }}</span>
                            </div>
                            <div class="mt-1" style="font-size:.7rem;color:var(--sol-base1);">
                                HP {{ number_format($mob['hp']) }}
                            </div>
                            @if(count($mob['drops']) > 0)
                                <div style="font-size:.67rem;color:var(--sol-cyan);">
                                    {{ count($mob['drops']) }} drops
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        @if($totalPages > 1)
            <nav aria-label="Navegação de páginas">
                <ul class="pagination justify-content-center flex-wrap">
                    {{-- Anterior --}}
                    <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('mobdb.index', array_merge(request()->except('page'), ['page' => $page - 1])) }}">Anterior</a>
                    </li>

                    @php
                        $start = max(1, $page - 3);
                        $end   = min($totalPages, $page + 3);
                    @endphp

                    @if($start > 1)
                        <li class="page-item"><a class="page-link" href="{{ route('mobdb.index', array_merge(request()->except('page'), ['page' => 1])) }}">1</a></li>
                        @if($start > 2) <li class="page-item disabled"><span class="page-link">...</span></li> @endif
                    @endif

                    @for($p = $start; $p <= $end; $p++)
                        <li class="page-item {{ $p === $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('mobdb.index', array_merge(request()->except('page'), ['page' => $p])) }}">{{ $p }}</a>
                        </li>
                    @endfor

                    @if($end < $totalPages)
                        @if($end < $totalPages - 1) <li class="page-item disabled"><span class="page-link">...</span></li> @endif
                        <li class="page-item"><a class="page-link" href="{{ route('mobdb.index', array_merge(request()->except('page'), ['page' => $totalPages])) }}">{{ $totalPages }}</a></li>
                    @endif

                    {{-- Próxima --}}
                    <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('mobdb.index', array_merge(request()->except('page'), ['page' => $page + 1])) }}">Proxima</a>
                    </li>
                </ul>
                <p class="text-center text-muted small">Pagina {{ $page }} de {{ $totalPages }} ({{ number_format($total) }} monstros)</p>
            </nav>
        @endif

    @else
        <div class="text-center py-5" style="color:var(--sol-base1);">
            <div style="font-size:3rem;">?</div>
            <h4 class="mt-3">Nenhum monstro encontrado</h4>
            <p class="small">Tente outro nome, ID ou remova os filtros.</p>
            <a href="{{ route('mobdb.index') }}" class="btn btn-outline-secondary mt-2">Ver todos</a>
        </div>
    @endif

</div>

<footer class="py-4 mt-4" style="border-top:1px solid var(--sol-border);color:var(--sol-base1);">
    <div class="container text-center small">
        RagnaRogue &mdash; Seed <code>{{ $activeSeed }}</code> &mdash; Database procedural gerada deterministicamente.
    </div>
</footer>
</body>
</html>
