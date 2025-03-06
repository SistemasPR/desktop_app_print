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
        /*Window::current()->onEvent('attempt-close', function () {
            $confirm = Dialog::confirm('¿Estás seguro de que quieres cerrar la aplicación?');
    
            if (!$confirm) {
                return; // No cerrar la ventana
            }
    
            Window::current()->close(); // Cierra la ventana si el usuario confirma
        });*/
    }
}
