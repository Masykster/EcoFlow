<?php

/*
 * Landing page (Astro + astro-laravel) regression tests.
 *
 * DB-free by design: the full render needs MySQL (Livewire calculator mount
 * queries categories/emission_factors, and one legacy migration uses
 * MySQL-only MODIFY syntax so sqlite testing cannot migrate). These tests
 * guard the wiring instead: route -> generated view -> compiled sections.
 */

test('home route serves the astro generated landing view', function () {
    expect(route('home', absolute: false))->toBe('/');

    $route = collect(app('router')->getRoutes())->firstWhere('uri', '/');
    expect($route->defaults['view'] ?? null)->toBe('astro.welcome');

    expect(view()->exists('astro.welcome'))->toBeTrue();
});

test('astro generated blade keeps landing sections and laravel hooks', function () {
    $compiled = file_get_contents(resource_path('views/astro/welcome.blade.php'));

    // Astro build output (landing content)
    foreach (['hero-media-container', 'tentang-kami', 'Mulai Hitung Jejakmu', 'cta-offset', 'faq-trigger', 'custom-cursor'] as $needle) {
        expect($compiled)->toContain($needle);
    }

    // Laravel runtime hooks (must survive Astro as raw Blade, unescaped)
    foreach (["@vite(['resources/css/app.css', 'resources/js/app.js'])", '@livewireStyles', '@livewireScripts', '@auth', '@endauth', '<livewire:carbon-calculator :isGuestMode="true" />', "{{ route('login') }}", "{{ date('Y') }}"] as $needle) {
        expect($compiled)->toContain($needle);
    }

    expect($compiled)->not->toContain('&#39;');
    expect($compiled)->not->toContain('&quot;');
});

test('legacy blade landing is kept as backup', function () {
    expect(view()->exists('welcome-legacy'))->toBeTrue();
});

test('landing page renders end to end without database', function () {
    // No RefreshDatabase on purpose: any DB query would throw
    // "no such table" on sqlite :memory:, proving the landing is DB-free.
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Mulai Hitung Jejakmu', false);
    $response->assertSee('Pertamax', false);
    $response->assertSee('Sign In', false);
});
