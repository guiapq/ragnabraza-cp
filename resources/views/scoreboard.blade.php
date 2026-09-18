<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15">
    <title>Torneio de Anime — Placar do Evento</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&family=JetBrains+Mono:wght@500;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #090a0f;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.95); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 2.5s infinite ease-in-out;
        }
    </style>
</head>
<body class="text-gray-100 min-h-screen flex flex-col justify-between p-6 md:p-10 selection:bg-amber-500 selection:text-black">

    {{-- Topo / Header do Evento --}}
    <header class="flex flex-col md:flex-row items-center justify-between border-b border-gray-800 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 flex items-center justify-center text-gray-950 font-black text-2xl shadow-lg shadow-amber-500/20">
                ⚔️
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-red-950/80 text-red-400 border border-red-800/60 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        AO VIVO NO EVENTO
                    </span>
                    <span class="text-xs font-mono text-gray-400">Auto-refresh 15s</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white mt-1">
                    TORNEIO ROGUELIKE <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-200">12H SPRINT</span>
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Seed da Rodada</span>
                <div class="text-xl font-black font-mono text-amber-400">{{ $seed }}</div>
            </div>
            <div class="h-10 w-px bg-gray-800"></div>
            <a href="/admin" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-bold rounded-xl border border-gray-700 transition">
                Painel Admin
            </a>
        </div>
    </header>

    {{-- Conteúdo Principal / Colunas da Arena --}}
    <main class="my-8 grid grid-cols-1 lg:grid-cols-2 gap-8 flex-1">
        
        {{-- Card Esquerda: Speedrun 99 --}}
        <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800/80 rounded-3xl p-6 md:p-8 flex flex-col justify-between shadow-2xl relative overflow-hidden group hover:border-emerald-500/40 transition duration-500">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚡</span>
                        <div>
                            <h2 class="text-xl font-black tracking-wide text-white">SPEEDRUN 1-99</h2>
                            <p class="text-xs text-gray-400">Menor tempo total de jogo até o nível máximo</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-emerald-950/60 text-emerald-400 text-xs font-mono font-bold rounded-xl border border-emerald-800/40">
                        {{ $speedruns->count() }} Finalistas
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($speedruns as $idx => $run)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-800/40 border border-gray-700/40 hover:bg-gray-800/80 transition">
                            <div class="flex items-center gap-4">
                                <span class="w-8 text-center font-mono font-black text-lg {{ $idx === 0 ? 'text-yellow-400' : ($idx === 1 ? 'text-gray-300' : ($idx === 2 ? 'text-amber-600' : 'text-gray-500')) }}">
                                    @if($idx === 0) 🥇
                                    @elseif($idx === 1) 🥈
                                    @elseif($idx === 2) 🥉
                                    @else #{{ $idx + 1 }}
                                    @endif
                                </span>
                                <div>
                                    <div class="font-black text-white text-base">{{ $run->name }}</div>
                                    <div class="text-xs text-gray-400">Concluído às {{ $run->achieved_at?->format('H:i:s') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-black text-emerald-400 text-lg tracking-wider">{{ $run->formatted_time }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-semibold">Tempo de Run</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-500">
                            <div class="text-4xl mb-3">🏃‍♂️💨</div>
                            <p class="font-semibold text-sm">Nenhum jogador alcançou o 99 ainda.</p>
                            <p class="text-xs text-gray-600 mt-1">A corrida está acontecendo nos mapas agora!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-800/60 flex items-center justify-between text-xs text-gray-500 font-mono">
                <span>Critério: Tempo de criação até 99</span>
                <span>Premiação ao final do evento</span>
            </div>
        </div>

        {{-- Card Direita: MVP Bounty Hunters --}}
        <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800/80 rounded-3xl p-6 md:p-8 flex flex-col justify-between shadow-2xl relative overflow-hidden group hover:border-amber-500/40 transition duration-500">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🏆</span>
                        <div>
                            <h2 class="text-xl font-black tracking-wide text-white">MVP BOUNTY HUNTERS</h2>
                            <p class="text-xs text-gray-400">Caçadores de Chefes e MVPs solo</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-amber-950/60 text-amber-400 text-xs font-mono font-bold rounded-xl border border-amber-800/40">
                        Top Caçadores
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($mvpBounties as $idx => $hunter)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-800/40 border border-gray-700/40 hover:bg-gray-800/80 transition">
                            <div class="flex items-center gap-4">
                                <span class="w-8 text-center font-mono font-black text-lg {{ $idx === 0 ? 'text-amber-400' : 'text-gray-500' }}">
                                    @if($idx === 0) 👑
                                    @else #{{ $idx + 1 }}
                                    @endif
                                </span>
                                <div>
                                    <div class="font-black text-white text-base">{{ $hunter->char_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $hunter->distinct_mvps }} MVPs distintos eliminados</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-black text-amber-400 text-lg">
                                    {{ $hunter->total_kills }} <span class="text-xs font-normal text-gray-400">KILLS</span>
                                </div>
                                <div class="text-[10px] text-gray-500 uppercase font-semibold">Pontuação de Chefe</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-500">
                            <div class="text-4xl mb-3">👹</div>
                            <p class="font-semibold text-sm">Nenhum MVP foi derrotado nesta rodada.</p>
                            <p class="text-xs text-gray-600 mt-1">Os chefes procedurais continuam intocados!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Feed de Abates Recentes --}}
            <div class="mt-6 pt-4 border-t border-gray-800/60">
                <span class="text-xs uppercase font-bold text-gray-400 mb-2 block tracking-wider">Últimos Abates</span>
                <div class="flex flex-wrap gap-2">
                    @forelse($recentKills as $k)
                        <span class="px-3 py-1 bg-gray-800 rounded-lg text-xs border border-gray-700/60 flex items-center gap-1.5">
                            <span class="text-red-400 font-bold">{{ $k->char_name }}</span>
                            <span class="text-gray-500">derrotou</span>
                            <span class="text-amber-300 font-semibold">{{ $k->mob_name }}</span>
                        </span>
                    @empty
                        <span class="text-xs text-gray-600">Aguardando primeiro abate de MVP...</span>
                    @endforelse
                </div>
            </div>
        </div>

    </main>

    {{-- Rodapé --}}
    <footer class="border-t border-gray-800/60 pt-4 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
        <div>
            Ragnarok Docker v2 • Roguelike 12h Sprint & Tournament Engine
        </div>
        <div class="flex items-center gap-4">
            <span>Rede Local do Evento</span>
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span>Servidor Operacional</span>
        </div>
    </footer>

</body>
</html>
