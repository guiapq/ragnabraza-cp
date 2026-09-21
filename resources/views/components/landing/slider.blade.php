@props(['seed' => 'zawarudo'])

<div class="card hero-solarized border-0 rounded-0 overflow-hidden shadow-sm mb-4 position-relative" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border-bottom: 2px solid #d3cbb7 !important;">
    <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
        <div class="d-flex flex-wrap gap-2 mb-2">
            <span class="badge text-bg-warning font-monospace text-uppercase px-2 py-1">Seed: {{ $seed }}</span>
            <span class="badge text-bg-info px-2 py-1">Roguelike SSF (Loop 12h)</span>
            <span class="badge text-bg-secondary px-2 py-1">rAthena Pre-Renewal</span>
        </div>

        <h1 class="display-6 fw-bold mb-2" style="color: #002b36;">
            RagnaRogue — Servidor Procedural Roguelike
        </h1>
        <p class="lead fs-6 mb-4" style="max-width: 780px; color: #586e75;">
            Mundos efêmeros gerados deterministicamente a partir de uma semente. Drops rebalanceados para progressão Solo Self-Found,
            lojas temáticas especializadas e cliente WebAssembly nativo executado diretamente no navegador.
        </p>

        <div class="d-flex flex-wrap gap-2">
            <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-warning fw-bold px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm">
                Jogar no roBrowser
            </a>
            <a href="{{ route('market.index') }}" class="btn btn-outline-info fw-bold px-4 py-2 d-inline-flex align-items-center gap-2">
                Mercado & Drops da Seed
            </a>
            <a href="{{ route('event.scoreboard') }}" class="btn btn-outline-secondary px-3 py-2 d-inline-flex align-items-center gap-1">
                Placar ao Vivo
            </a>
        </div>
    </div>
</div>
