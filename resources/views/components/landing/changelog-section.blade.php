@props(['changelogs' => []])

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="h4 fw-bold mb-0">
            Notas de Atualização (Changelog)
        </h3>
        <span class="badge text-bg-secondary font-monospace">v2.0 Estável</span>
    </div>

    <div class="list-group list-group-flush border rounded shadow-sm overflow-hidden">
        @forelse($changelogs as $log)
            <div class="list-group-item p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $log['badge_class'] ?? 'text-bg-info' }}" style="min-width: 85px;">
                        {{ $log['badge'] }}
                    </span>
                    <span class="fs-6" style="color: #073642;">{{ $log['title'] }}</span>
                </div>
                <small class="text-secondary font-monospace text-nowrap">{{ $log['time'] }}</small>
            </div>
        @empty
            <div class="list-group-item text-secondary p-3">
                Nenhum changelog recente registrado.
            </div>
        @endforelse
    </div>
</div>
