<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Banner do Evento --}}
        <div class="p-6 bg-gradient-to-r from-amber-950 via-gray-900 to-indigo-950 border border-amber-500/30 rounded-2xl shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold tracking-wider text-amber-400 uppercase">Torneio Local de Convenção & Anime Fest</span>
                <h2 class="text-2xl font-black text-white mt-1">
                    Arena do Evento — Seed: <span class="text-amber-400 font-mono">{{ $this->getActiveSeed() }}</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    Ranking ao vivo dos jogadores na run roguelike solo. Registros computados via telemetria nativa do rAthena.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="/scoreboard" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-400 hover:from-amber-400 hover:to-yellow-300 text-gray-950 font-black text-sm rounded-xl shadow-lg transition-all duration-200 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Modo Telão (Kiosk TV)
                </a>
            </div>
        </div>

        {{-- Grid com as Tabelas de Speedrun e MVP Bounty --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Coluna 1: Speedrun 99 --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="p-5 bg-gray-800/60 border-b border-gray-700/60 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">⚡</span>
                        <h3 class="font-bold text-white text-base">Speedrun 1-99 (Menor Tempo)</h3>
                    </div>
                    <span class="text-xs font-mono text-emerald-400 bg-emerald-950/60 border border-emerald-800/40 px-2.5 py-1 rounded-full">
                        {{ $this->getSpeedruns()->count() }} no Hall
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-gray-800/40 text-xs uppercase font-bold text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-5 py-3">Pos</th>
                                <th class="px-5 py-3">Personagem</th>
                                <th class="px-5 py-3">Tempo Real</th>
                                <th class="px-5 py-3">Conquista</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($this->getSpeedruns() as $idx => $run)
                                <tr class="hover:bg-gray-800/40 transition-colors">
                                    <td class="px-5 py-3 font-mono font-bold">
                                        @if($idx === 0) 🥇 1º
                                        @elseif($idx === 1) 🥈 2º
                                        @elseif($idx === 2) 🥉 3º
                                        @else {{ $idx + 1 }}º
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 font-bold text-white">{{ $run->name }}</td>
                                    <td class="px-5 py-3 font-mono text-amber-400 font-black">{{ $run->formatted_time }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-400">{{ $run->achieved_at?->format('H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">
                                        Nenhum jogador alcançou o nível 99 nesta rodada ainda. Seja o primeiro!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Coluna 2: MVP Bounty Hunter --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="p-5 bg-gray-800/60 border-b border-gray-700/60 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">🏆</span>
                        <h3 class="font-bold text-white text-base">MVP Bounty Hunters</h3>
                    </div>
                    <span class="text-xs font-mono text-amber-400 bg-amber-950/60 border border-amber-800/40 px-2.5 py-1 rounded-full">
                        Top Caçadores
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-gray-800/40 text-xs uppercase font-bold text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-5 py-3">Pos</th>
                                <th class="px-5 py-3">Caçador</th>
                                <th class="px-5 py-3">Abates</th>
                                <th class="px-5 py-3">Último Kill</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($this->getMvpBounties() as $idx => $bounty)
                                <tr class="hover:bg-gray-800/40 transition-colors">
                                    <td class="px-5 py-3 font-mono font-bold">
                                        @if($idx === 0) 👑 1º
                                        @elseif($idx === 1) 🥈 2º
                                        @elseif($idx === 2) 🥉 3º
                                        @else {{ $idx + 1 }}º
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 font-bold text-white">{{ $bounty->char_name }}</td>
                                    <td class="px-5 py-3 font-mono text-emerald-400 font-bold">
                                        {{ $bounty->total_kills }} MVP{{ $bounty->total_kills > 1 ? 's' : '' }}
                                        <span class="text-xs text-gray-400 font-normal">({{ $bounty->distinct_mvps }} tipos)</span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($bounty->last_kill)->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">
                                        Nenhum MVP derrotado ainda. Os chefes estão dominando o mapa!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Feed dos Últimos Abates de MVP --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-5 bg-gray-800/60 border-b border-gray-700/60 flex items-center justify-between">
                <h3 class="font-bold text-white text-base">Últimos Abates de MVP na Rodada</h3>
                <span class="text-xs text-gray-400">Tempo real</span>
            </div>

            <div class="divide-y divide-gray-800">
                @forelse($this->getRecentKills() as $kill)
                    <div class="px-6 py-3.5 flex items-center justify-between text-sm hover:bg-gray-800/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-red-950/60 border border-red-800/40 rounded-lg text-red-400">💀</span>
                            <div>
                                <span class="font-bold text-white">{{ $kill->char_name }}</span>
                                <span class="text-gray-400 text-xs">derrotou</span>
                                <span class="font-bold text-amber-400">{{ $kill->mob_name }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 font-mono">{{ $kill->killed_at?->format('H:i:s') }}</span>
                    </div>
                @empty
                    <div class="px-6 py-6 text-center text-gray-500 text-sm">
                        Nenhum abate de MVP registrado recentemente.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
