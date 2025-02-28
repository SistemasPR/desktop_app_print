<?php

namespace App\Providers;

use Native\Laravel\Dialog;
use Native\Laravel\Facades\Window;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        //
        Window::onClose(function ($window) {
            $result = Dialog::confirm('¿Estás seguro de que quieres cerrar la aplicación?');
    
            if (!$result) {
                $window->preventClose(); // Evita que la ventana se cierre
            }
        });
    }
}
