@props(['status' => [], 'onlinePlayers' => 0])

<div class="card mb-3 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Status dos Serviços</span>
        <span class="badge text-bg-success">rAthena Pre-Re</span>
    </div>
    <div class="card-body py-3">
        <div class="row g-2 text-center">
            <div class="col-3">
                <div class="p-2 rounded border" style="background-color: var(--sol-base3);">
                    <div class="fw-bold {{ ($status['login'] ?? true) ? 'text-success' : 'text-danger' }} small">
                        {{ ($status['login'] ?? true) ? 'ONLINE' : 'OFFLINE' }}
                    </div>
                    <div class="text-secondary" style="font-size: 11px;">Login:6900</div>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 rounded border" style="background-color: var(--sol-base3);">
                    <div class="fw-bold {{ ($status['char'] ?? true) ? 'text-success' : 'text-danger' }} small">
                        {{ ($status['char'] ?? true) ? 'ONLINE' : 'OFFLINE' }}
                    </div>
                    <div class="text-secondary" style="font-size: 11px;">Char:6121</div>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 rounded border" style="background-color: var(--sol-base3);">
                    <div class="fw-bold {{ ($status['map'] ?? true) ? 'text-success' : 'text-danger' }} small">
                        {{ ($status['map'] ?? true) ? 'ONLINE' : 'OFFLINE' }}
                    </div>
                    <div class="text-secondary" style="font-size: 11px;">Map:5121</div>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 rounded border" style="background-color: var(--sol-base3);">
                    <div class="fw-bold text-warning small font-monospace">
                        {{ $onlinePlayers }}
                    </div>
                    <div class="text-secondary" style="font-size: 11px;">Online</div>
                </div>
            </div>
        </div>
    </div>
</div>
