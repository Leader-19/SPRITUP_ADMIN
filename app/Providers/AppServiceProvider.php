<?php

namespace App\Providers;

use App\Interfaces\Categories\CategoriesInterface;
use App\Interfaces\Documents\DocumentsInterface;
use App\Repositories\Categories\CategoriesRepository;
use App\Repositories\Documents\DocumentsRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Register Repository and interface
        $this->app->bind(
            DocumentsInterface::class,
            DocumentsRepository::class,

        );

        $this->app->bind(
            CategoriesInterface::class,
            CategoriesRepository::class
        );
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
          if (app()->environment('production')) {
        URL::forceScheme('https');
    }
    }
}
