<?php

use App\Livewire\CarbonCalculator;
use Livewire\Livewire;

/*
 * Guest calculator (landing page) must work with zero database access.
 * These tests intentionally do NOT use RefreshDatabase: with sqlite
 * :memory: and no migrations, any query would throw "no such table",
 * so passing proves the guest path never touches the DB.
 */

test('guest calculator mounts with static emission factors', function () {
    Livewire::test(CarbonCalculator::class, ['isGuestMode' => true])
        ->assertOk()
        ->assertSee('Pertamax', false)
        ->call('setTab', 'kendaraan')
        ->assertSee('Motor Bensin', false)
        ->call('setTab', 'makanan')
        ->assertSee('Daging Sapi', false);
});

test('guest calculator computes bahan bakar without database', function () {
    // Default bahan bakar = Pertamax @ 2.33 kg CO2e/liter
    Livewire::test(CarbonCalculator::class, ['isGuestMode' => true])
        ->set('bb_liter', 10)
        ->assertSet('previewCo2e', 23.3);
});

test('guest calculator computes kendaraan without database', function () {
    // Motor Bensin: (km / km_per_liter) * EF / pax = (120 / 40) * 2.33
    Livewire::test(CarbonCalculator::class, ['isGuestMode' => true])
        ->call('setTab', 'kendaraan')
        ->set('kd_km', 120)
        ->set('kd_eff', 40)
        ->assertSet('previewCo2e', 6.99);
});

test('guest calculator refuses to save without login', function () {
    Livewire::test(CarbonCalculator::class, ['isGuestMode' => true])
        ->set('bb_liter', 10)
        ->call('saveTransaction')
        ->assertSet('saved', false)
        ->assertSet('errorMsg', 'Masuk untuk menyimpan riwayat');
});
