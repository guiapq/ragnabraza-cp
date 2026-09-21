@props(['user'])

<div class="card shadow-sm border mb-4">
    <div class="card-body py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-circle d-flex align-items-center justify-content-center font-bold" style="width: 42px; height: 42px; background-color: var(--sol-base2); color: var(--sol-blue);">
                Acc
            </div>
            <div>
                <div class="fw-bold fs-6" style="color: #002b36;">{{ $user->userid }}</div>
                <div class="small text-secondary">ID da Conta: #{{ $user->getKey() }}</div>
            </div>
        </div>

        <div class="d-flex flex-column">
            <div class="small text-secondary">Último Login</div>
            <div class="fw-semibold small" style="color: #073642;">{{ $user->lastlogin?->format('d/m/Y H:i') ?: 'Primeiro Acesso' }}</div>
        </div>

        <div class="d-flex flex-column">
            <div class="small text-secondary">Último IP</div>
            <div class="fw-semibold small font-monospace text-secondary" style="filter: blur(1.5px);">{{ $user->last_ip ?: '127.0.0.1' }}</div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($user->isOnline())
                <span class="badge text-bg-success px-2 py-1">
                    Online
                </span>
            @else
                <span class="badge text-bg-secondary px-2 py-1">Offline</span>
            @endif

            <span class="badge text-bg-info px-2 py-1">
                {{ number_format($user->getCashPoints(), 0, ',', '.') }} ROPs
            </span>

            @if($user->group_id >= 99)
                <span class="badge text-bg-danger px-2 py-1">GM</span>
            @elseif($user->vip_time > 0)
                <span class="badge text-bg-warning px-2 py-1">VIP</span>
            @endif
        </div>
    </div>
</div>
