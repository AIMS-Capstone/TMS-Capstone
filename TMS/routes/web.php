<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password', function () {
    return view('auth/reset-password');
});

Route::get('/register-success-page', function () {
    return view('components/register-success-page');
});

Route::get('/check-mail', function () {
    return view('components/check-mail');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
