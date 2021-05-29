<?php

namespace App\Providers;

use App\Repositories\AcquisitionRepository;
use App\Repositories\AcquisitionTypeRepository;
use App\Repositories\CollectionCategoryRepository;
use App\Repositories\CollectionTypeRepository;
use App\Repositories\Interfaces\AcquisitionRepositoryInterface;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use App\Repositories\Interfaces\CollectionCategoryRepositoryInterface;
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

        $this->app->bind(
            'App\Repositories\Interfaces\RepositoryEloquentInterface',
            'App\Repositories\ModelRepository'
        );

        $this->app->bind(
            'App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface',
            'App\Repositories\AcquisitionTypeRepository'
        );

        $this->app->bind(
            'App\Repositories\Interfaces\AcquisitionRepositoryInterface',
            'App\Repositories\AcquisitionRepository'
        );

        $this->app->bind(
            'App\Repositories\Interfaces\CollectionTypeRepositoryInterface',
            'App\Repositories\CollectionTypeRepository'
        );

        $this->app->bind(
            'App\Repositories\Interfaces\CollectionCategoryRepositoryInterface',
            'App\Repositories\CollectionCategoryRepository'
        );
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
