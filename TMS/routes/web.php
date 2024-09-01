<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomVerificationController;
use App\Http\Controllers\sendPasswordReset;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/reset-password', function () {
    return view('auth/reset-password');
});

Route::post('/custom-password-email', [sendPasswordReset::class, 'sendPasswordReset'])->name('custom.password.email');

Route::get('/check-mail', function () {
    return view('components/check-mail');
});

Route::get('/email/verify/{id}/{hash}', [CustomVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1']);

Route::get('/register-success-page', function () {
    return view('components/register-success-page');
})->name('register-success-page');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/recycle-bin', function () {
        return view('recycle-bin');
    })->name('recycle-bin');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/transactions', function () {
        return view('transactions');
    })->name('transactions');
    Route::get('/coa', function () {
        return view('coa');
    })->name('coa');
    Route::get('/financial-reports', function () {
        return view('financial-reports');
    })->name('financial-reports');
    Route::get('/predictive-analytics', function () {
        return view('predictive-analytics');
    })->name('predictive-analytics');
    Route::get('/org-setup', function () {
        return view('org-setup');
    })->name('org-setup');
    Route::get('/add-user', function () {
        return view('add-user');
    })->name('add-user');
});
