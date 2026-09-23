<x-filament-panels::page>
    <div class="space-y-6" style="color: #073642;">
        {{-- Banner do Mundo e Seed Ativa --}}
        <div class="p-6 border rounded-2xl shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="background: linear-gradient(135deg, #fdf6e3 0%, #eee8d5 100%); border-color: #d3cbb7;">
            <div>
                <span class="text-xs font-semibold tracking-wider uppercase font-mono" style="color: #268bd2;">Procedural Intelligence Suite</span>
                <h2 class="text-2xl font-black mt-1" style="color: #002b36;">
                    Mundo Ativo: <span class="font-mono" style="color: #b58900;">{{ $this->getActiveSeed() }}</span>
                </h2>
                <p class="text-sm mt-1" style="color: #586e75;">
                    Enciclopédia de monstros, itens procedurais, análise de afixos e rotas de leveling da seed atual.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('market.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 font-bold text-sm rounded-xl border transition shadow-sm" style="background-color: #fdf6e3; color: #073642; border-color: #d3cbb7;">
                    Abrir Mercado Livre
                </a>
                <a href="http://{{ request()->getHost() }}:8001" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 font-bold text-sm rounded-xl shadow transition" style="background-color: #b58900; color: #ffffff;">
                    Jogar no roBrowser
                </a>
            </div>
        </div>

        {{-- Navegador de Abas --}}
        <div class="flex flex-wrap gap-2 border-b pb-3" style="border-color: #d3cbb7;">
            <button
                wire:click="setTab('mobs')"
                type="button"
                class="px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 {{ $activeTab === 'mobs' ? 'shadow-sm' : '' }}"
                style="{{ $activeTab === 'mobs' ? 'background-color: #268bd2; color: #ffffff;' : 'background-color: #eee8d5; color: #586e75;' }}"
            >
                Monstros & Drops
            </button>
            <button
                wire:click="setTab('items')"
                type="button"
                class="px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 {{ $activeTab === 'items' ? 'shadow-sm' : '' }}"
                style="{{ $activeTab === 'items' ? 'background-color: #268bd2; color: #ffffff;' : 'background-color: #eee8d5; color: #586e75;' }}"
            >
                Itens & Lojas
            </button>
            <button
                wire:click="setTab('meta')"
                type="button"
                class="px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 {{ $activeTab === 'meta' ? 'shadow-sm' : '' }}"
                style="{{ $activeTab === 'meta' ? 'background-color: #268bd2; color: #ffffff;' : 'background-color: #eee8d5; color: #586e75;' }}"
            >
                Meta Analyzer da Seed
            </button>
            <button
                wire:click="setTab('routes')"
                type="button"
                class="px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 {{ $activeTab === 'routes' ? 'shadow-sm' : '' }}"
                style="{{ $activeTab === 'routes' ? 'background-color: #268bd2; color: #ffffff;' : 'background-color: #eee8d5; color: #586e75;' }}"
            >
                Rotas de EXP (Leveling)
            </button>
        </div>

        {{-- ABA 1: MONSTROS & DROPS --}}
        @if($activeTab === 'mobs')
            <div class="space-y-4">
                <div class="w-full">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar monstro por nome ou ID..."
                        class="w-full border text-sm rounded-xl p-3 shadow-inner"
                        style="background-color: #fdf6e3; border-color: #d3cbb7; color: #073642;"
                    />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" style="color: #073642;">
                                <thead class="text-xs uppercase font-bold border-b" style="background-color: #eee8d5; color: #002b36; border-color: #d3cbb7;">
                                    <tr>
                                        <th class="px-4 py-3">Monstro</th>
                                        <th class="px-4 py-3">Nv</th>
                                        <th class="px-4 py-3">HP</th>
                                        <th class="px-4 py-3">DEF / MDEF</th>
                                        <th class="px-4 py-3">Raca / Elem</th>
                                        <th class="px-4 py-3 text-right">Acao</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y" style="border-color: #d3cbb7;">
                                    @forelse($this->getMobs() as $mob)
                                        <tr class="hover:bg-amber-50 transition cursor-pointer {{ $viewMobId === $mob['id'] ? 'bg-amber-100 font-bold' : '' }}" wire:click="selectMob({{ $mob['id'] }})">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $mob['icon_url'] ?? $mob['sprite_url'] }}" width="24" height="24" alt="" style="image-rendering: pixelated; object-fit: contain;" onerror="if(!this.dataset.fallback){this.dataset.fallback='1';this.src='{{ $mob['sprite_url'] }}';}else{this.style.display='none';}">
                                                    <div>
                                                        <div class="font-bold" style="color: #002b36;">{{ $mob['name'] }}</div>
                                                        <div class="text-[11px] font-mono text-gray-500">ID {{ $mob['id'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $mob['level'] }}</td>
                                            <td class="px-4 py-3 font-mono text-xs font-bold" style="color: #859900;">{{ number_format($mob['hp']) }}</td>
                                            <td class="px-4 py-3 text-xs font-mono">
                                                <span>{{ $mob['def'] }}</span> / <span>{{ $mob['mdef'] }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                <span class="px-1.5 py-0.5 rounded border text-[11px]" style="background-color: #eee8d5; border-color: #d3cbb7;">{{ $mob['race'] }}</span>
                                                <span class="px-1.5 py-0.5 rounded border text-[11px]" style="background-color: #eee8d5; border-color: #d3cbb7; color: #b58900;">{{ $mob['element'] }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button
                                                    type="button"
                                                    wire:click.stop="selectMob({{ $mob['id'] }})"
                                                    class="px-2.5 py-1 text-xs font-bold rounded border transition"
                                                    style="background-color: #eee8d5; border-color: #d3cbb7; color: #073642;"
                                                >
                                                    Drops ({{ count($mob['drops']) }})
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                Nenhum monstro encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        @if($selectedMob = $this->getSelectedMob())
                            <div class="border rounded-2xl p-5 shadow-sm sticky top-4 space-y-4" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                                <div class="flex items-center gap-3 border-b pb-4" style="border-color: #d3cbb7;">
                                    <img src="{{ $selectedMob['icon_url'] ?? $selectedMob['sprite_url'] }}" width="48" height="48" alt="" style="image-rendering: pixelated; object-fit: contain;" class="rounded-xl p-1 border" style="background-color: #eee8d5; border-color: #d3cbb7;" onload="if(this.naturalWidth === 57 && this.naturalHeight === 57){this.src='https://static.divine-pride.net/images/mobs/png/1002.png';}" onerror="this.src='https://static.divine-pride.net/images/mobs/png/1002.png';">
                                    <div>
                                        <h3 class="text-lg font-black" style="color: #002b36;">{{ $selectedMob['name'] }}</h3>
                                        <div class="text-xs font-mono text-gray-500">
                                            ID {{ $selectedMob['id'] }} • Lv {{ $selectedMob['level'] }} • {{ $selectedMob['race'] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs font-mono">
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">HP:</span> <strong style="color: #859900;">{{ number_format($selectedMob['hp']) }}</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">EXP/JEXP:</span> <strong style="color: #b58900;">{{ $selectedMob['exp'] }}/{{ $selectedMob['jexp'] }}</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">DEF/MDEF:</span> <strong style="color: #6c71c4;">{{ $selectedMob['def'] }}/{{ $selectedMob['mdef'] }}</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">Elemento:</span> <strong style="color: #cb4b16;">{{ $selectedMob['element'] }}</strong>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold uppercase text-gray-500 mb-2">Mapas de Spawn:</h4>
                                    <div class="flex flex-wrap gap-1 max-h-24 overflow-y-auto">
                                        @forelse($selectedMob['maps'] as $map)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-mono border" style="background-color: #eee8d5; border-color: #d3cbb7;">{{ $map }}</span>
                                        @empty
                                            <span class="text-xs text-gray-500 italic">Spawn em masmorras ou eventos.</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold uppercase text-gray-500 mb-2">Drops Recalculados pela Seed:</h4>
                                    <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                                        @forelse($selectedMob['drops'] as $drop)
                                            <div class="flex items-center justify-between p-2 rounded-lg text-xs border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $drop['sprite_url'] }}" width="20" height="20" alt="" style="image-rendering: pixelated;" onerror="this.src='https://static.divine-pride.net/images/items/item/501.png'">
                                                    <span class="font-medium" style="color: #073642;">{{ $drop['item_name'] }}</span>
                                                </div>
                                                <span class="font-mono font-bold" style="color: #b58900;">{{ $drop['rate_percent'] }}%</span>
                                            </div>
                                        @empty
                                            <p class="text-xs text-gray-500 italic">Sem drops registrados.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="pt-2 border-t text-right" style="border-color: #d3cbb7;">
                                    <a href="{{ $selectedMob['divine_url'] }}" target="_blank" class="text-xs font-bold" style="color: #268bd2;">
                                        Ver Ficha no Divine Pride →
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="border rounded-2xl p-8 text-center text-gray-500 shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                                Selecione um monstro na tabela para inspecionar drops e mapas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ABA 2: ITENS & LOJAS --}}
        @if($activeTab === 'items')
            <div class="space-y-4">
                <div class="w-full">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar item por nome, aegis ou ID..."
                        class="w-full border text-sm rounded-xl p-3 shadow-inner"
                        style="background-color: #fdf6e3; border-color: #d3cbb7; color: #073642;"
                    />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm" style="color: #073642;">
                                <thead class="text-xs uppercase font-bold border-b" style="background-color: #eee8d5; color: #002b36; border-color: #d3cbb7;">
                                    <tr>
                                        <th class="px-4 py-3">Item</th>
                                        <th class="px-4 py-3">Tipo</th>
                                        <th class="px-4 py-3">Peso</th>
                                        <th class="px-4 py-3">Preco</th>
                                        <th class="px-4 py-3">Fontes</th>
                                        <th class="px-4 py-3 text-right">Acao</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y" style="border-color: #d3cbb7;">
                                    @forelse($this->getItems() as $item)
                                        <tr class="hover:bg-amber-50 transition cursor-pointer {{ $viewItemId === $item['id'] ? 'bg-amber-100 font-bold' : '' }}" wire:click="selectItem({{ $item['id'] }})">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $item['sprite_url'] }}" width="24" height="24" alt="" style="image-rendering: pixelated;" onerror="this.src='https://static.divine-pride.net/images/items/item/501.png'">
                                                    <div>
                                                        <div class="font-bold" style="color: #002b36;">{{ $item['name'] }}</div>
                                                        <div class="text-[11px] font-mono text-gray-500">ID {{ $item['id'] }} • {{ $item['aegis'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs"><span class="px-2 py-0.5 rounded border" style="background-color: #eee8d5; border-color: #d3cbb7;">{{ $item['type'] }}</span></td>
                                            <td class="px-4 py-3 font-mono text-xs">{{ $item['weight'] }}</td>
                                            <td class="px-4 py-3 font-mono text-xs font-bold" style="color: #b58900;">{{ number_format($item['buy']) }}z</td>
                                            <td class="px-4 py-3 text-xs">
                                                <span class="px-1.5 py-0.5 rounded border text-[11px]" style="background-color: #eee8d5; border-color: #d3cbb7; color: #859900;">{{ $item['dropped_by_count'] }} drops</span>
                                                <span class="px-1.5 py-0.5 rounded border text-[11px]" style="background-color: #eee8d5; border-color: #d3cbb7; color: #268bd2;">{{ $item['shops_count'] }} lojas</span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button
                                                    type="button"
                                                    wire:click.stop="selectItem({{ $item['id'] }})"
                                                    class="px-2.5 py-1 text-xs font-bold rounded border transition"
                                                    style="background-color: #eee8d5; border-color: #d3cbb7; color: #073642;"
                                                >
                                                    Inspecionar
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhum item encontrado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        @if($selectedItem = $this->getSelectedItem())
                            <div class="border rounded-2xl p-5 shadow-sm sticky top-4 space-y-4" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                                <div class="flex items-center gap-3 border-b pb-4" style="border-color: #d3cbb7;">
                                    <img src="{{ $selectedItem['sprite_url'] }}" width="40" height="40" alt="" style="image-rendering: pixelated;" class="rounded-xl p-1 border" style="background-color: #eee8d5; border-color: #d3cbb7;" onerror="this.src='https://static.divine-pride.net/images/items/item/501.png'">
                                    <div>
                                        <h3 class="text-base font-black" style="color: #002b36;">{{ $selectedItem['name'] }}</h3>
                                        <div class="text-xs font-mono text-gray-500">
                                            ID {{ $selectedItem['id'] }} • {{ $selectedItem['aegis'] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs font-mono">
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">Tipo:</span> <strong>{{ $selectedItem['type'] }}</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">Peso:</span> <strong>{{ $selectedItem['weight'] }}</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">Preco Compra:</span> <strong style="color: #b58900;">{{ number_format($selectedItem['buy']) }}z</strong>
                                    </div>
                                    <div class="p-2 rounded-lg border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                        <span class="text-gray-500">Preco Venda:</span> <strong>{{ number_format($selectedItem['sell']) }}z</strong>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold uppercase text-gray-500 mb-2">Monstros que Dropam:</h4>
                                    <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                        @forelse($selectedItem['dropped_by'] as $dMob)
                                            <div class="flex items-center justify-between p-2 rounded-lg text-xs border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $dMob['icon_url'] ?? $dMob['sprite_url'] }}" width="16" height="16" alt="" style="image-rendering: pixelated; object-fit: contain;" onerror="this.style.display='none'">
                                                    <span>{{ $dMob['mob_name'] }} <small class="text-gray-500 font-mono">(Lv {{ $dMob['mob_level'] }})</small></span>
                                                </div>
                                                <span class="font-mono font-bold" style="color: #859900;">{{ $dMob['rate_percent'] }}%</span>
                                            </div>
                                        @empty
                                            <p class="text-xs text-gray-500 italic">Nenhum monstro dropa este item.</p>
                                        @endforelse
                                    </div>
                                </div>

                                @if(count($selectedItem['shops']) > 0)
                                    <div>
                                        <h4 class="text-xs font-bold uppercase text-gray-500 mb-2">Vendido em Lojas de NPCs:</h4>
                                        <div class="space-y-1 max-h-36 overflow-y-auto pr-1">
                                            @foreach($selectedItem['shops'] as $shop)
                                                <div class="p-2 rounded-lg text-xs flex justify-between items-center border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                                    <span>{{ $shop['shop_name'] }} ({{ $shop['map'] }})</span>
                                                    <span class="font-mono" style="color: #b58900;">{{ number_format($shop['price']) }}z</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-2 border-t text-right" style="border-color: #d3cbb7;">
                                    <a href="{{ $selectedItem['divine_url'] }}" target="_blank" class="text-xs font-bold" style="color: #268bd2;">
                                        Ver Ficha no Divine Pride →
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="border rounded-2xl p-8 text-center text-gray-500 shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                                Selecione um item na lista para ver lojas e drops.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ABA 3: META ANALYZER DA SEED --}}
        @if($activeTab === 'meta')
            @php $meta = $this->getMetaAnalysis(); @endphp
            <div class="space-y-6">
                <div class="p-6 border rounded-2xl shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                    <span class="text-xs font-bold uppercase tracking-wider font-mono" style="color: #268bd2;">Diagnostico Geral do Mundo</span>
                    <h3 class="text-2xl font-black mt-1" style="color: #002b36;">{{ $meta['primary_meta'] }}</h3>
                    <p class="text-sm mt-2" style="color: #586e75;">
                        O algoritmo analisa os afixos de todos os equipamentos gerados pela seed para estimar viabilidade e direcionamento das builds.
                    </p>
                </div>

                {{-- Contagem de Afixos --}}
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach($meta['stats_count'] as $stat => $count)
                        <div class="p-4 border rounded-xl text-center shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                            <div class="text-xs font-mono text-gray-500">{{ $stat }}</div>
                            <div class="text-xl font-black font-mono mt-1" style="color: #b58900;">{{ $count }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5">ocorrencias</div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 border rounded-2xl shadow-sm space-y-4" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                        <h4 class="text-base font-bold" style="color: #859900;">Classes Recomendadas na Seed</h4>
                        <div class="space-y-3">
                            @foreach($meta['recommended'] as $rec)
                                <div class="p-3 rounded-xl border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                    <div class="font-bold text-sm" style="color: #859900;">{{ $rec['class'] }}</div>
                                    <div class="text-xs mt-1" style="color: #586e75;">{{ $rec['reason'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 border rounded-2xl shadow-sm space-y-4" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                        <h4 class="text-base font-bold" style="color: #dc322f;">Classes em Dificuldade (Cursed)</h4>
                        <div class="space-y-3">
                            @forelse($meta['cursed'] as $cur)
                                <div class="p-3 rounded-xl border" style="background-color: #eee8d5; border-color: #d3cbb7;">
                                    <div class="font-bold text-sm" style="color: #dc322f;">{{ $cur['type'] }}</div>
                                    <div class="text-xs mt-1 text-gray-500">{{ $cur['desc'] }}</div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-gray-500 italic">
                                    Nenhum deficit critico encontrado. O mundo esta equilibrado.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ABA 4: ROTAS DE EXP (LEVELING GUIDE) --}}
        @if($activeTab === 'routes')
            @php $routesData = $this->getExpRoutes(); @endphp
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach(['Lv 1–25', 'Lv 26–50', 'Lv 51–75', 'Lv 76–99'] as $tier)
                        <button
                            type="button"
                            wire:click="$set('selectedTier', '{{ $tier }}')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition border"
                            style="{{ $selectedTier === $tier ? 'background-color: #b58900; color: #ffffff; border-color: #b58900;' : 'background-color: #eee8d5; color: #073642; border-color: #d3cbb7;' }}"
                        >
                            {{ $tier }} ({{ count($routesData['tiers'][$tier] ?? []) }} mapas)
                        </button>
                    @endforeach
                </div>

                <div class="border rounded-2xl overflow-hidden shadow-sm" style="background-color: #fdf6e3; border-color: #d3cbb7;">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm" style="color: #073642;">
                            <thead class="text-xs uppercase font-bold border-b" style="background-color: #eee8d5; color: #002b36; border-color: #d3cbb7;">
                                <tr>
                                    <th class="px-4 py-3">Rank</th>
                                    <th class="px-4 py-3">Mapa</th>
                                    <th class="px-4 py-3">Eficiencia (Score)</th>
                                    <th class="px-4 py-3">Nv Medio</th>
                                    <th class="px-4 py-3">Densidade</th>
                                    <th class="px-4 py-3">Amostra de Monstros</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: #d3cbb7;">
                                @forelse($routesData['tiers'][$selectedTier] ?? [] as $index => $route)
                                    <tr class="hover:bg-amber-50 transition">
                                        <td class="px-4 py-3 font-mono font-bold" style="color: #b58900;">{{ $index + 1 }}º</td>
                                        <td class="px-4 py-3 font-mono font-bold" style="color: #002b36;">{{ $route['map'] }}</td>
                                        <td class="px-4 py-3 font-mono font-bold text-xs" style="color: #859900;">{{ $route['score'] }} pts</td>
                                        <td class="px-4 py-3 font-mono text-xs">Nv {{ $route['avg_level'] }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $route['total_mobs'] }} mobs</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-1">
                                                @foreach($route['mobs'] as $rmob)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-mono border" style="background-color: #eee8d5; border-color: #d3cbb7;" title="{{ $rmob['name'] }} (Lv {{ $rmob['level'] }}) x{{ $rmob['count'] }}">
                                                        <img src="{{ $rmob['icon_url'] ?? $rmob['sprite_url'] }}" width="14" height="14" alt="" style="image-rendering: pixelated; object-fit: contain;" onerror="this.style.display='none'">
                                                        <span>{{ $rmob['count'] }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhum mapa nesta faixa de nivel.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
