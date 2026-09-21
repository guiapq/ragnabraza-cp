<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #002b36;">
            {{ __('Visão Geral do Personagem') }}
        </h2>
    </x-slot>

    <!-- Banner Roguelike & World Seed -->
    <div class="card shadow-sm border mb-4 hero-solarized" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 60%, #e6dfc8 100%); border-color: #d3cbb7;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge text-bg-danger text-uppercase fw-bold px-2 py-1" style="letter-spacing: 0.5px;">Temporada Roguelike</span>
                        <span class="badge text-bg-secondary font-monospace">
                            Seed: <strong class="text-warning font-monospace">{{ $seed ?? 'default' }}</strong>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #002b36;">{{ config('app.name', 'RagnaRogue') }}</h3>
                    <p class="mb-0 small text-secondary">
                        Corrida de 12 horas: mundo procedural com drops, atributos e monstros customizados por Seed.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="http://{{ request()->getHost() }}:8001" target="_blank"
                       class="btn btn-warning fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                        Iniciar roBrowser
                    </a>
                    <a href="{{ route('event.scoreboard') }}"
                       class="btn btn-outline-secondary fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                        Placar ao Vivo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Metadados da Conta -->
    <x-game.account-info-bar :user="auth()->user()"/>

    <!-- Seção de Personagens -->
    <div class="card shadow-sm border mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold d-flex align-items-center gap-2" style="color: #002b36;">
                Meus Personagens
            </h5>
            <span class="badge text-bg-secondary">{{ auth()->user()->characters->count() }} / 9 Slots</span>
        </div>
        <div class="card-body p-4">
            <div id="characters">
                <div class="row g-4">
                    @forelse(auth()->user()->characters as $character)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center py-2">
                                    <span class="badge text-bg-secondary">#{{ $loop->iteration }}</span>
                                    @if($character->online)
                                        <span class="badge text-bg-success">
                                            Online
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">Offline</span>
                                    @endif
                                </div>

                                <div class="p-3 text-center" style="background-color: var(--sol-base3);">
                                    <img src="{{ asset('images/character_preview.png') }}"
                                         class="img-fluid" style="max-height: 140px; object-fit: contain;"
                                         alt="{{ $character->name }}"/>
                                </div>

                                <div class="card-body text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-1" style="color: #002b36;">{{ $character->name }}</h4>
                                        <div class="mb-2">
                                            @if(auth()->user()->group_id >= 99)
                                                <span class="badge text-bg-danger">Staff GM</span>
                                            @elseif(auth()->user()->vip_time > 0)
                                                <span class="badge text-bg-warning">VIP</span>
                                            @else
                                                <span class="badge text-bg-secondary">Aventureiro</span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-center flex-wrap gap-1 mb-3">
                                            <span class="badge text-bg-secondary">Lv. {{ $character->base_level }}/{{ $character->job_level }}</span>
                                            <span class="badge text-bg-primary">{{ $character->class->getName() }}</span>
                                            <span class="badge text-bg-warning">{{ number_format($character->zeny, 0, ',', '.') }} z</span>
                                        </div>

                                        <div class="small p-2 rounded border text-start" style="background-color: var(--sol-base3);">
                                            <div class="d-flex justify-content-between">
                                                <span>Localização:</span>
                                                <strong class="font-monospace" style="color: #002b36;">{{ $character->last_map ?: 'prontera' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between text-secondary">
                                                <span>Coordenadas:</span>
                                                <span class="font-monospace">({{ $character->last_x }}, {{ $character->last_y }})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-top p-2 d-flex justify-content-between align-items-center">
                                    <a href="{{ route('game.character.settings', $character->getKey()) }}"
                                       class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                                       title="Preferências do Personagem">
                                        Ajustes
                                    </a>

                                    <form method="POST" action="{{ route('game.character.unstuck', $character->getKey()) }}" class="d-inline m-0">
                                        @csrf
                                        @if($character->online)
                                            <button type="button" class="btn btn-outline-danger btn-sm opacity-50"
                                                    title="Desconecte o personagem do jogo para desatolar" disabled>
                                                Desatolar
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Deseja desatolar {{ $character->name }} para o centro de Prontera (156, 191)?')">
                                                Desatolar
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-secondary">
                            <p class="mb-2 fs-5">Nenhum personagem criado ainda.</p>
                            <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="btn btn-primary fw-bold">
                                Entrar no roBrowser e Criar Personagem
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Seção do Armazém Kafra -->
    <div class="card shadow-sm border mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold d-flex align-items-center gap-2" style="color: #002b36;">
                Armazém Kafra (Storage)
            </h5>
            @php
                $storageItems = auth()->user()->storageItems()->paginate(15);
            @endphp
            <span class="badge text-bg-secondary">{{ $storageItems->total() }} Itens Guardados</span>
        </div>
        <div class="card-body p-0">
            @if($storageItems->isEmpty())
                <div class="p-5 text-center text-secondary">
                    <p class="mb-0 fw-medium">Nenhum item armazenado no Kafra Storage no momento.</p>
                    <small class="text-secondary">Itens guardados no jogo via Kafra aparecerão aqui.</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;" class="text-center">Ícone</th>
                                <th>Item</th>
                                <th class="text-center">Refino</th>
                                <th class="text-center">Cartas</th>
                                <th class="text-center">Condição</th>
                                <th class="text-end">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($storageItems as $storage)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('storage/item/' . $storage->nameid . '.png') }}"
                                         onerror="this.onerror=null; this.src='{{ asset('images/item-placeholder.svg') }}';"
                                         class="rounded p-1 border" style="width: 36px; height: 36px; object-fit: contain; background-color: var(--sol-base3);"
                                         alt="Item #{{ $storage->nameid }}">
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color: #002b36;">
                                        {{ $storage->item?->name_english ?? ('Item #' . $storage->nameid) }}
                                        @if(($storage->item?->slots ?? 0) > 0)
                                            <span class="text-secondary small">[{{ $storage->item->slots }}]</span>
                                        @endif
                                    </div>
                                    <span class="badge text-bg-secondary font-monospace" style="font-size: 0.7rem;">ID: {{ $storage->nameid }}</span>
                                </td>
                                <td class="text-center">
                                    @if($storage->refine > 0)
                                        <span class="badge text-bg-warning fw-bold">+{{ $storage->refine }}</span>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($storage->hasCards)
                                        @foreach($storage->cards()->getEquipedCards() as $card)
                                            <span class="badge text-bg-info">{{ $card['amount'] }}x {{ $card['name'] }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($storage->identify)
                                        <span class="badge text-bg-success">Identificado</span>
                                    @else
                                        <span class="badge text-bg-secondary">Não identificado</span>
                                    @endif
                                    @if($storage->attribute)
                                        <span class="badge text-bg-danger">Quebrado</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    <span class="badge text-bg-primary fs-6">{{ number_format($storage->amount, 0, ',', '.') }}x</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $storageItems->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
