<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-5">⚔️</span>
            <span class="fw-bold fs-6">{{ config('app.name', 'RagnaRogue') }}</span>
        </div>
        <span class="badge bg-primary text-white" style="font-size: 0.65rem;">PORTAL</span>
    </div>
    <div class="card-body p-2">
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('game.overview') }}" class="nav-link {{ request()->routeIs('game.overview') ? 'active' : 'link-body-emphasis' }} d-flex align-items-center gap-2">
                    <span>👤</span>
                    <span class="fw-medium">Meus Personagens</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="nav-link text-primary d-flex align-items-center justify-content-between">
                    <span class="d-flex align-items-center gap-2">
                        <span>🎮</span>
                        <span class="fw-bold">Jogar roBrowser</span>
                    </span>
                    <span class="badge bg-primary text-white" style="font-size: 0.65rem;">WEB</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('event.scoreboard') }}" class="nav-link {{ request()->routeIs('event.scoreboard') ? 'active' : 'link-body-emphasis' }} d-flex align-items-center gap-2">
                    <span>🏆</span>
                    <span class="fw-medium">Placar do Torneio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('game.online-players') }}" class="nav-link {{ request()->routeIs('game.online-players') ? 'active' : 'link-body-emphasis' }} d-flex align-items-center gap-2">
                    <span>🟢</span>
                    <span class="fw-medium">Jogadores Online</span>
                </a>
            </li>
            @if(auth()->check() && auth()->user()->group_id >= 99)
                <li class="border-top my-2"></li>
                <li class="nav-item">
                    <a href="/admin" class="nav-link text-danger d-flex align-items-center gap-2">
                        <span>🛡️</span>
                        <span class="fw-bold">Painel GM (Admin)</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
