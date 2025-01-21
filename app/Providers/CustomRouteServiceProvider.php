<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CustomRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes(): void
    {
        // Default API routes
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                // Load versioned API routes
                Route::prefix('v1')
                    ->group(base_path('routes/api/v1/route.php'));
            });
    }
}
