<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Jetstream;
use App\Http\Controllers\ClientAuthController;


class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
        
        $this->registerClientRoutes();

    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
    protected function registerClientRoutes()
    {
        Route::group(['middleware' => ['web']], function () {
            // Custom Client Login and Registration Routes
            Route::get('/client/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
            Route::post('/client/login', [ClientAuthController::class, 'login'])->name('client.login.submit');
            Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');

            Route::get('/client/register', [ClientAuthController::class, 'showRegistrationForm'])->name('client.register');
            Route::post('/client/register', [ClientAuthController::class, 'register'])->name('client.register.submit');

            Route::get('/client/forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])->name('client.password.request');
            Route::post('/client/forgot-password', [ClientAuthController::class, 'sendResetLink'])->name('client.password.email');
        });
    }

}
