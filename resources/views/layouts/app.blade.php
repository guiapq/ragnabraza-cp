<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        @livewireStyles
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-light">
        <x-banner />
        <x-navigation-menu/>


        <!-- Page Content -->
        <main class="container my-5">
            <div class="row">
                <div class="col-3 d-sm-block d-md-block d-none ">
                    <x-sidebar-menu></x-sidebar-menu>
                </div>
                <div class="col">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                            <strong>✨ Sucesso:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm" role="alert">
                            <strong>⚠️ Atenção:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{ $slot }}
                </div>
            </div>

        </main>

        @stack('modals')

        @livewireScripts

        @stack('scripts')

    </body>
</html>
