<?php

namespace App\Providers;

use App\Repositories\AcquisitionTypeRepository;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use App\Repositories\ModelRepository;
use App\Repositories\RepositoryEloquentInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RepositoryEloquentInterface::class, ModelRepository::class);
        $this->app->bind(AcquisitionTypeRepositoryInterface::class, AcquisitionTypeRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
