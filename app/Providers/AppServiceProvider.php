<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

use App\Repositories\Interfaces\PlantRepositoryInterface;
use App\Repositories\PlantRepository;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;

use App\Repositories\Interfaces\StatisticsRepositoryInterface;
use App\Repositories\StatisticsRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        $this->app->bind(PlantRepositoryInterface::class, PlantRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        $this->app->bind(StatisticsRepositoryInterface::class, StatisticsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
