<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;
use App\Repositories\RepositoryEloquentInterface;
use Illuminate\Database\Eloquent\Model;

class ModelRepository implements InterfacesRepositoryEloquentInterface
{

    /** 
     * @var Model $model Base Model of Repository 
     * 
     */
    protected $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function delete($id)
    {
        $modelForDelete = $this->model->findById($id);
        return $modelForDelete->delete();
    }

    /**
     * @param $id
     * @return Model
     */
    public function findById($id)
    {
        $this->model->find($id);
    }

    /**
     * 
     * @return Collection 
     */
    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * @param  $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, array $attributesForUpdate)
    {
        $modelForUpdate = $this->model->findById($id);
        return $modelForUpdate->update($attributesForUpdate);
    }
}
