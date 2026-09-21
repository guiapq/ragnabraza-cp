<x-filament-panels::page>
    <div class="space-y-6" style="color: #073642;">
        {{-- Banner do Evento --}}
        <div class="p-6 border rounded-2xl shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 100%); border-color: #d3cbb7;">
            <div>
                <span class="text-xs font-semibold tracking-wider uppercase font-mono" style="color: #b58900;">Torneio Roguelike & Sprint 12H</span>
                <h2 class="text-2xl font-black mt-1" style="color: #002b36;">
                    Arena do Evento — Seed: <span class="font-mono" style="color: #b58900;">{{ $this->getActiveSeed() }}</span>
                </h2>
                <p class="text-sm mt-1" style="color: #586e75;">
                    Ranking ao vivo dos jogadores na run roguelike solo. Registros computados via telemetria nativa do rAthena.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="/scoreboard" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 font-black text-sm rounded-xl shadow transition" style="background-color: #b58900; color: #ffffff;">
                    Modo Telão (Placar Público)
                </a>
            </div>
        </div>

        {{-- Grid com as Tabelas de Speedrun e MVP Bounty --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Coluna 1: Speedrun 99 --}}
            <div class="border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                <div class="p-5 border-b flex items-center justify-between" style="background-color: #eee8d5; border-color: #d3cbb7;">
                    <div>
                        <h3 class="font-bold text-base" style="color: #002b36;">Speedrun 1-99 (Menor Tempo)</h3>
                    </div>
                    <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full border" style="background-color: #fdf6e3; color: #859900; border-color: #859900;">
                        {{ $this->getSpeedruns()->count() }} no Hall
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" style="color: #073642;">
                        <thead class="text-xs uppercase font-bold border-b" style="background-color: #eee8d5; color: #002b36; border-color: #d3cbb7;">
                            <tr>
                                <th class="px-5 py-3">Pos</th>
                                <th class="px-5 py-3">Personagem</th>
                                <th class="px-5 py-3">Tempo Real</th>
                                <th class="px-5 py-3">Conquista</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: #d3cbb7;">
                            @forelse($this->getSpeedruns() as $idx => $run)
                                <tr class="hover:bg-amber-50 transition">
                                    <td class="px-5 py-3 font-mono font-bold" style="color: {{ $idx === 0 ? '#b58900' : ($idx === 1 ? '#268bd2' : ($idx === 2 ? '#cb4b16' : '#657b83')) }};">
                                        {{ $idx + 1 }}º
                                    </td>
                                    <td class="px-5 py-3 font-bold" style="color: #002b36;">{{ $run->name }}</td>
                                    <td class="px-5 py-3 font-mono font-black" style="color: #859900;">{{ $run->formatted_time }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $run->achieved_at?->format('H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">
                                        Nenhum jogador alcançou o nível 99 nesta rodada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Coluna 2: MVP Bounty Hunter --}}
            <div class="border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                <div class="p-5 border-b flex items-center justify-between" style="background-color: #eee8d5; border-color: #d3cbb7;">
                    <div>
                        <h3 class="font-bold text-base" style="color: #002b36;">Caçadores de MVP (Bounty)</h3>
                    </div>
                    <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full border" style="background-color: #fdf6e3; color: #dc322f; border-color: #dc322f;">
                        Top Caçadores
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" style="color: #073642;">
                        <thead class="text-xs uppercase font-bold border-b" style="background-color: #eee8d5; color: #002b36; border-color: #d3cbb7;">
                            <tr>
                                <th class="px-5 py-3">Pos</th>
                                <th class="px-5 py-3">Caçador</th>
                                <th class="px-5 py-3">Abates</th>
                                <th class="px-5 py-3">Último Abate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: #d3cbb7;">
                            @forelse($this->getMvpBounties() as $idx => $bounty)
                                <tr class="hover:bg-amber-50 transition">
                                    <td class="px-5 py-3 font-mono font-bold" style="color: {{ $idx === 0 ? '#dc322f' : '#657b83' }};">
                                        {{ $idx + 1 }}º
                                    </td>
                                    <td class="px-5 py-3 font-bold" style="color: #002b36;">{{ $bounty->char_name }}</td>
                                    <td class="px-5 py-3 font-mono font-bold" style="color: #dc322f;">
                                        {{ $bounty->total_kills }} MVP{{ $bounty->total_kills > 1 ? 's' : '' }}
                                        <span class="text-xs text-gray-500 font-normal">({{ $bounty->distinct_mvps }} tipos)</span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($bounty->last_kill)->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">
                                        Nenhum MVP derrotado ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Feed dos Últimos Abates de MVP --}}
        <div class="border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
            <div class="p-5 border-b flex items-center justify-between" style="background-color: #eee8d5; border-color: #d3cbb7;">
                <h3 class="font-bold text-base" style="color: #002b36;">Últimos Abates de MVP na Rodada</h3>
                <span class="text-xs text-gray-500">Tempo real</span>
            </div>

            <div class="divide-y" style="border-color: #d3cbb7;">
                @forelse($this->getRecentKills() as $kill)
                    <div class="px-6 py-3.5 flex items-center justify-between text-sm hover:bg-amber-50 transition">
                        <div>
                            <span class="font-bold" style="color: #002b36;">{{ $kill->char_name }}</span>
                            <span class="text-gray-500 text-xs">derrotou</span>
                            <span class="font-bold font-mono" style="color: #cb4b16;">{{ $kill->mob_name }}</span>
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
