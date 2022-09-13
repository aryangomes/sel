<?php

namespace App\Actions\CrudModelOperations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetAll
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     *      
     * @return Collection
     */
    public function __invoke()
    {
        $collectionModel = [];
        try {
            DB::beginTransaction();
            $collectionModel = $this->model->all();
        } catch (\Exception $exception) {
        }

        return $collectionModel;
    }
}
