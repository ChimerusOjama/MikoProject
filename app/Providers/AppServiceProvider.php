<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

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
    // public function boot(): void
    // {
    //     //
    //     if (app()->environment('local')) {
    //     $host = request()->getSchemeAndHttpHost(); // récupère ex: https://1234.ngrok.io
    //     Config::set('app.url', $host);             // met à jour la config dynamiquement
    //     URL::forceRootUrl($host);                  // force Laravel à utiliser ce host
    //     URL::forceScheme('https');                 // force HTTPS (Ngrok est toujours en https)
    //     } else {
    //         URL::forceScheme('https'); // force uniquement en prod ou staging
    //     }
    //     // Uncomment the following lines if you want to force HTTPS in production or staging environments

    //     // if (env(key: 'APP_ENV') !=='local') {
    //     // URL::forceScheme(scheme:'https');
    //     // }
    // }

    // boot 2 selection du canal(ngrak ou artisan serve)
    public function boot(): void
    {
        // Détection de l’environnement local (php artisan serve ou Ngrok)
        if (app()->environment('local')) {
            $host = request()->getSchemeAndHttpHost(); // Ex: http://127.0.0.1:8000 OU https://xxxx.ngrok.io

            // Si Ngrok est détecté (commence par https)
            if (str_starts_with($host, 'https://')) {
                Config::set('app.url', $host);
                URL::forceRootUrl($host);
                URL::forceScheme('https');
            } else {
                // Sinon, on est en local (localhost classique)
                Config::set('app.url', $host);
                URL::forceRootUrl($host);
            }

        } else {
            // En production ou staging → toujours HTTPS
            URL::forceScheme('https');
        }
    }

}
