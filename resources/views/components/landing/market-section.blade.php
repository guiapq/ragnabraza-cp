@props(['marketSample' => []])

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="h5 mb-0 fw-bold">
            Mercado Livre & Lojas (Vending)
        </h4>
        <a href="{{ route('market.index') }}" class="btn btn-sm btn-outline-info">
            Ver Todos no Catálogo →
        </a>
    </div>

    <div class="card-body p-3">
        {{-- Busca rápida de mercado --}}
        <form method="GET" action="{{ route('market.index') }}" class="mb-3">
            <div class="input-group">
                <input
                    type="text"
                    name="q"
                    placeholder="Pesquisar item no mercado ou por drop (ex: Carta, Poção, Lança)..."
                    class="form-control form-control-sm font-monospace"
                >
                <button type="submit" class="btn btn-sm btn-primary fw-bold">Buscar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 align-middle small">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">#</th>
                        <th>Item</th>
                        <th>Vendedor / Loja</th>
                        <th class="text-center">Qtd</th>
                        <th class="text-end">Preço</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marketSample as $vending)
                        <tr>
                            <td class="text-center p-1">
                                <img
                                    src="{{ $vending['sprite_url'] ?? 'https://static.divine-pride.net/images/items/item/501.png' }}"
                                    width="24"
                                    height="24"
                                    alt=""
                                    style="image-rendering: pixelated;"
                                    onerror="this.src='https://static.divine-pride.net/images/items/item/501.png'"
                                >
                            </td>
                            <td>
                                <a href="{{ route('market.index', ['q' => $vending['item_name']]) }}" class="fw-bold text-decoration-none" style="color: #073642;">
                                    {{ $vending['item_name'] }}
                                </a>
                                @if(!empty($vending['refine']))
                                    <span class="badge text-bg-warning ms-1">+{{ $vending['refine'] }}</span>
                                @endif
                            </td>
                            <td class="text-secondary">
                                <div><strong style="color: #002b36;">{{ $vending['merchant_name'] }}</strong></div>
                                <small class="text-secondary font-monospace">{{ $vending['map'] ?? 'prontera' }}</small>
                            </td>
                            <td class="text-center font-monospace">
                                <span class="badge text-bg-secondary">{{ $vending['amount'] }}x</span>
                            </td>
                            <td class="text-end font-monospace">
                                <strong class="text-warning">{{ number_format($vending['price']) }}z</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-3">
                                Nenhuma loja aberta no momento. <a href="{{ route('market.index') }}" class="text-info">Consulte o catálogo da seed.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
