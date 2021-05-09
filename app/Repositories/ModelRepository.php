<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
     * @param Model $model
     * @return void
     */
    public function delete($model)
    {
        return $model->delete();
    }

    /**
     * @param $id
     * @return Model
     */
    public function findById($id)
    {
        return $this->model->find($id);
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
     * @param Model $model
     * @param array $attributesForUpdate
     * @return Model
     */
    public function update(array $attributesForUpdate, Model $model)
    {

        return $model->update($attributesForUpdate);
    }


    /**
     * @param Model $model
     * @return Resource
     */
    public function getResourceModel($model)
    {
        return new Resource($model);
    }

    /**
     * @param $id
     * @return ResourceCollection
     */
    public function getResourceCollectionModel()
    {
        return new ResourceCollection($this->model->all());
    }
}
