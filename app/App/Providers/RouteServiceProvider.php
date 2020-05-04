<?php

namespace App\Providers;

use Domain\Geolocation\Models\Geolocation;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Soyhuce\ModelInjection\BindModels;
use Soyhuce\Rules\DbRules;

class RouteServiceProvider extends ServiceProvider
{
    use BindModels;

    public function boot(): void
    {
        parent::boot();

        $this->bindModels();
    }

    protected function bindModels(): void
    {
        $this->bindModel('geolocation', Geolocation::class, rules(DbRules::string()));
    }

    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
