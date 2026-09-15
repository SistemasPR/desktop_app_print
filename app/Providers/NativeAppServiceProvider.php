<?php

namespace App\Providers;

use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;
use Native\Desktop\Facades\App;
use Native\Desktop\Facades\Alert;

class NativeAppServiceProvider implements ProvidesPhpIni
{

    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        App::openAtLogin(true);

        Window::open("main")->url(route('view.configuration'))->title('Aplicativo de Impresión')
        ->width(800)
        ->height(800)
        ->hideMenu()
        ->fullscreenable(false)
        ->closable(false) 
        ->maximizable(false);

    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'opcache.enable' => '1',
            'opcache.enable_cli' => '1',
            'opcache.memory_consumption' => '128',
            'opcache.max_accelerated_files' => '10000',
            'opcache.validate_timestamps' => '0', // en producción
        ];
    }
}
