<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="w-100" style="max-width: 440px;">
        <div class="text-center mb-4">
            {{ $logo }}
        </div>

        <div class="card shadow border-0" style="background-color: var(--sol-base2, #eee8d5); border-radius: 0.5rem;">
            {{ $slot }}
        </div>
    </div>
</div>