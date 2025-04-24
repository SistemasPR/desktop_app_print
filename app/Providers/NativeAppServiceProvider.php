<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\Alert;

class NativeAppServiceProvider implements ProvidesPhpIni
{

    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()->url(route('view.login'))->title('Aplicativo de Impresión')
        ->width(800)
        ->height(500)
        ->maximizable(false)
        ->closable(false);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
