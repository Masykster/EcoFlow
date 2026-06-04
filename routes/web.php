<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard',    'dashboard')->name('dashboard');
    Route::view('calculator',   'pages.calculator')->name('calculator');
    Route::view('history',      'pages.history')->name('history');
    Route::view('achievements', 'pages.achievements')->name('achievements');
});

Route::get('auth/google/redirect', function () {
    return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('auth/google/callback', function () {
    try {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('login')->withErrors(['email' => 'Google authentication failed: ' . $e->getMessage()]);
    }

    $user = \App\Models\User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name'              => $googleUser->getName(),
            'password'          => bcrypt(\Illuminate\Support\Str::random(24)),
            'api_key'           => \Illuminate\Support\Str::random(64),
            'email_verified_at' => now(),
        ]
    );

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('dashboard');
})->name('google.callback');

require __DIR__.'/settings.php';

