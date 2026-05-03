<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard',    'dashboard')->name('dashboard');
    Route::view('calculator',   'pages.calculator')->name('calculator');
    Route::view('history',      'pages.history')->name('history');
    Route::view('achievements', 'pages.achievements')->name('achievements');
});

require __DIR__.'/settings.php';

