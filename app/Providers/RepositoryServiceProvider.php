<?php

namespace App\Providers;

use App\Repositories\AcquisitionRepository;
use App\Repositories\AcquisitionTypeRepository;
use App\Repositories\CollectionTypeRepository;
use App\Repositories\Interfaces\AcquisitionRepositoryInterface;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use App\Repositories\Interfaces\CollectionTypeRepositoryInterface;
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
        $this->app->bind(AcquisitionRepositoryInterface::class, AcquisitionRepository::class);
        $this->app->bind(CollectionTypeRepositoryInterface::class, CollectionTypeRepository::class);
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
