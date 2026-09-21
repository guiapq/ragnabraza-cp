<nav class="navbar navbar-expand-md navbar-dark text-bg-dark">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand me-4 fw-bold text-primary-emphasis d-flex align-items-center gap-2" href="{{ route('game.overview') }}">
            <x-application-mark width="32"/>
            <span>{{ config('app.name', 'RagnaRogue') }}</span>
        </a>
        <div class="d-flex flex-row">
            <div class="navbar-toggler border-0">
                <x-layout.user-info is-mobile="true"></x-layout.user-info>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto align-items-center gap-1">
                <x-nav-link href="{{ route('game.overview') }}" :active="request()->routeIs('game.overview')">
                    {{ __('Personagens') }}
                </x-nav-link>
                <x-nav-link href="{{ route('event.scoreboard') }}" :active="request()->routeIs('event.scoreboard')">
                    {{ __('Placar ao Vivo') }}
                </x-nav-link>
                <x-nav-link href="{{ route('market.index') }}" :active="request()->routeIs('market.*')">
                    {{ __('Mercado & Itens') }}
                </x-nav-link>
                <x-nav-link href="{{ route('game.online-players') }}" :active="request()->routeIs('game.online-players')">
                    {{ __('Quem está Online') }}
                </x-nav-link>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-sm btn-primary fw-bold text-white d-inline-flex align-items-center gap-1 shadow-sm"
                       href="http://{{ request()->getHost() }}:8001" target="_blank" title="Abrir roBrowser em nova aba">
                        {{ __('Jogar no Navegador') }}
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav align-items-center gap-2">
                @auth
                    @if(auth()->user()->group_id >= 99)
                        <li class="nav-item">
                            <a class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" href="/admin">
                                Admin
                            </a>
                        </li>
                    @endif

                    <!-- Settings Dropdown -->
                    <x-dropdown id="settingsDropdown">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <x-layout.user-info is-mobile={{false}}></x-layout.user-info>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <h6 class="dropdown-header small text-muted">
                                {{ __('Conta do Jogador') }}
                            </h6>

                            <x-dropdown-link href="{{ route('game.overview') }}">
                                {{ __('Visão Geral da Conta') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('event.scoreboard') }}">
                                {{ __('Placar do Torneio') }}
                            </x-dropdown-link>

                            <hr class="dropdown-divider">

                            <!-- Authentication -->
                            <x-dropdown-link href="{{ route('logout') }}"
                                             onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                {{ __('Desconectar / Log out') }}
                            </x-dropdown-link>
                            <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                @csrf
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </ul>
        </div>
    </div>
</nav>
