@props(['stats' => []])

<div class="row g-2 justify-content-center text-center my-3">
    <div class="col-6 col-md">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <h4 class="card-title font-monospace text-info mb-1">{{ number_format($stats['characters'] ?? 0) }}</h4>
                <p class="card-text text-secondary small mb-0">Personagens Criados</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <h4 class="card-title font-monospace text-danger mb-1">{{ number_format($stats['accounts'] ?? 0) }}</h4>
                <p class="card-text text-secondary small mb-0">Contas Registradas</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <h4 class="card-title font-monospace text-warning mb-1">{{ $stats['zeny'] ?? '0' }}z</h4>
                <p class="card-text text-secondary small mb-0">Zeny em Circulação</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <h4 class="card-title font-monospace text-primary mb-1">{{ number_format($stats['mvp_kills'] ?? 0) }}</h4>
                <p class="card-text text-secondary small mb-0">MVPs Derrotados</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card shadow-sm h-100">
            <div class="card-body p-3">
                <h4 class="card-title font-monospace text-success mb-1">{{ $stats['seed'] ?? 'default' }}</h4>
                <p class="card-text text-secondary small mb-0">Seed do Mundo</p>
            </div>
        </div>
    </div>
</div>
