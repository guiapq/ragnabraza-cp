<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger mb-3 py-2 px-3" style="background-color: #fdf0ec; border-color: var(--sol-red, #dc322f); color: var(--sol-red, #dc322f); border-radius: 0.4rem; font-size: 0.88rem;">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success mb-3 py-2 px-3" style="font-size: 0.88rem; border-radius: 0.4rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="login_username" class="form-label fw-semibold" style="color: var(--sol-base01, #586e75); font-size: 0.85rem; letter-spacing: 0.03em; text-transform: uppercase;">
                        Usuario
                    </label>
                    <input
                        id="login_username"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="username"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        style="background-color: var(--sol-base3, #fdf6e3); border-color: var(--sol-base1, #93a1a1); color: var(--sol-base00, #657b83); border-radius: 0.4rem;"
                    />
                </div>

                <div class="mb-4">
                    <label for="login_password" class="form-label fw-semibold" style="color: var(--sol-base01, #586e75); font-size: 0.85rem; letter-spacing: 0.03em; text-transform: uppercase;">
                        Senha
                    </label>
                    <input
                        id="login_password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        style="background-color: var(--sol-base3, #fdf6e3); border-color: var(--sol-base1, #93a1a1); color: var(--sol-base00, #657b83); border-radius: 0.4rem;"
                    />
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember_me" style="border-color: var(--sol-base1, #93a1a1);">
                    <label class="form-check-label" for="remember_me" style="color: var(--sol-base1, #93a1a1); font-size: 0.85rem;">
                        Manter conectado
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn fw-semibold" style="background-color: var(--sol-green, #859900); color: #fff; border-radius: 0.4rem; padding: 0.6rem; letter-spacing: 0.03em;">
                        Entrar
                    </button>
                </div>
            </form>

            <div class="mt-4 pt-3 text-center" style="border-top: 1px solid var(--sol-base2, #eee8d5);">
                <small style="color: var(--sol-base1, #93a1a1); font-size: 0.8rem;">
                    Use o mesmo usuario e senha do cliente do jogo.
                </small>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
