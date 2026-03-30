<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        \Illuminate\Pagination\Paginator::useBootstrapFour();
        
        // Appliquer les limites PHP depuis le .env
        if ($memoryLimit = env('php_memory_limit')) {
            ini_set('memory_limit', $memoryLimit);
        }

        // Test de log pour confirmer que les logs fonctionnent
        \Illuminate\Support\Facades\Log::debug('Requête reçue', [
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'has_file' => request()->hasFile('image') || request()->hasFile('video'),
            'all_input' => request()->all(),
            'files_keys' => array_keys(request()->allFiles())
        ]);

    }
}
