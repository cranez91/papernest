<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;
use Inertia\Inertia;

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
        Model::unguard();

        Inertia::share([
            'appUrl' => config('app.url'),
        ]);

        if (App::environment('production')) {
            // En producción, especificar que el build está en el public real
            $buildPath = env('VITE_BUILD_PATH');

            if ($buildPath) {
                Vite::useBuildDirectory($buildPath);
            }

            URL::forceScheme('https');
        }
    }
}
