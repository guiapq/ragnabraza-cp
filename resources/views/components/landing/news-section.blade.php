@props(['news' => []])

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="h4 fw-bold mb-0">
            Notícias & Notas do Mundo
        </h3>
        <span class="badge text-bg-secondary font-monospace">Temporada Ativa</span>
    </div>

    <div class="row g-3">
        @forelse($news as $article)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <span class="badge {{ $article['badge_color'] ?? 'text-bg-info' }}">{{ $article['category'] }}</span>
                        <small class="text-secondary font-monospace">{{ $article['time'] }}</small>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold fs-6 mb-2">
                                {{ $article['title'] }}
                            </h5>
                            <p class="card-text text-secondary small mb-3">
                                {{ $article['excerpt'] }}
                            </p>
                        </div>
                        <div class="border-top pt-2 d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Autor: {{ $article['author'] }}</small>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary py-0 px-2"
                                data-bs-toggle="modal"
                                data-bs-target="#newsModal{{ $article['id'] }}"
                            >
                                Ler Mais
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal de Leitura Completa do Artigo --}}
                <div class="modal fade" id="newsModal{{ $article['id'] }}" tabindex="-1" aria-labelledby="newsModalLabel{{ $article['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="newsModalLabel{{ $article['id'] }}">
                                    {{ $article['title'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 d-flex gap-2">
                                    <span class="badge {{ $article['badge_color'] }}">{{ $article['category'] }}</span>
                                    <small class="text-secondary">Por {{ $article['author'] }} • {{ $article['time'] }}</small>
                                </div>
                                <p class="lead fs-6">{{ $article['excerpt'] }}</p>
                                <p class="text-secondary">{{ $article['content'] }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary text-secondary">
                    Nenhum comunicado no momento.
                </div>
            </div>
        @endforelse
    </div>
</div>
