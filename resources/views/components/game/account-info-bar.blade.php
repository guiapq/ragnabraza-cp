@props(['user'])

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary-subtle text-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <span class="fs-5">👤</span>
            </div>
            <div>
                <div class="fw-bold fs-6 text-dark">{{ $user->userid }}</div>
                <div class="small text-muted">ID da Conta: #{{ $user->getKey() }}</div>
            </div>
        </div>

        <div class="d-flex flex-column">
            <div class="small text-muted">Último Login</div>
            <div class="fw-semibold text-dark small">{{ $user->lastlogin?->format('d/m/Y H:i') ?: 'Primeiro Acesso' }}</div>
        </div>

        <div class="d-flex flex-column">
            <div class="small text-muted">Último IP</div>
            <div class="fw-semibold text-dark small font-monospace" style="filter: blur(1.5px);">{{ $user->last_ip ?: '127.0.0.1' }}</div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($user->isOnline())
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <span class="spinner-grow spinner-grow-sm me-1" style="width: 6px; height: 6px;" role="status"></span>
                    Online
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Offline</span>
            @endif

            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                🪙 {{ number_format($user->getCashPoints(), 0, ',', '.') }} ROPs
            </span>

            @if($user->group_id >= 99)
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">🛡️ GM</span>
            @elseif($user->vip_time > 0)
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">⭐ VIP</span>
            @endif
        </div>
    </div>
</div>
