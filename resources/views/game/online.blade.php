<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #002b36;">
            {{ __('Quem está Online') }}
        </h2>
    </x-slot>

    <x-game.account-info-bar :user="auth()->user()"/>

    <div class="card shadow-sm border mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold" style="color: #002b36;">
                Jogadores Online ({{ $charactersOnline->total() }} no servidor)
            </h5>
            <span class="badge text-bg-success">Tempo Real</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Personagem</th>
                            <th>Classe</th>
                            <th>Base / Job</th>
                            <th>Guilda</th>
                            <th>Mapa</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($charactersOnline as $character)
                        <tr>
                            <td class="fw-bold" style="color: #002b36;">{{ $character->settings->showCharacterMap ? $character->name : 'Oculto' }}</td>
                            <td><span class="badge text-bg-primary">{{ $character->class->getName() }}</span></td>
                            <td class="font-monospace">{{ $character->base_level . ' / ' . $character->job_level }}</td>
                            <td class="text-secondary">-</td>
                            <td class="font-monospace text-secondary">{{ $character->settings->showCharacterMap ? ($character->last_map ?: 'prontera') : 'Oculto' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">
                                Nenhum jogador conectado no momento.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $charactersOnline->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
