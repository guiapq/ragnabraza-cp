<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Banner do Mundo e Seed Ativa --}}
        <div class="p-6 bg-gradient-to-r from-gray-900 via-gray-800 to-indigo-950 border border-gray-700/50 rounded-2xl shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold tracking-wider text-indigo-400 uppercase">Procedural World Engine</span>
                <h2 class="text-2xl font-black text-white mt-1">
                    Mundo Ativo: <span class="text-amber-400 font-mono">{{ $this->getActiveSeed() }}</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Os dados abaixo são extraídos em tempo real dos arquivos gerados pelo seu pipeline procedural de rAthena.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="http://localhost:8001" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold text-sm rounded-xl shadow-lg transition-all duration-200 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jogar no roBrowser
                </a>
            </div>
        </div>

        {{-- Barra de Busca --}}
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Pesquisar monstro por nome ou ID..."
                    class="w-full bg-gray-900/80 border border-gray-700 text-white text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 p-3 shadow-inner"
                />
            </div>
        </div>

        {{-- Tabela de Monstros Procedurais --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-gray-800/80 text-xs uppercase font-bold text-gray-400 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">Level</th>
                            <th class="px-6 py-4">HP</th>
                            <th class="px-6 py-4">DEF / MDEF</th>
                            <th class="px-6 py-4">Raça / Elemento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($this->getMobs() as $mob)
                            <tr class="hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-amber-400 font-semibold">{{ $mob['id'] }}</td>
                                <td class="px-6 py-4 font-bold text-white">{{ $mob['name'] }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 bg-gray-800 rounded-md text-xs font-mono">{{ $mob['level'] }}</span></td>
                                <td class="px-6 py-4 font-mono text-emerald-400">{{ $mob['hp'] }}</td>
                                <td class="px-6 py-4 font-mono">{{ $mob['def'] }} / {{ $mob['mdef'] }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-indigo-300">Raça {{ $mob['race'] }} | Elem {{ $mob['element'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Nenhum monstro encontrado ou base procedural ainda não gerada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
