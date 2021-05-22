<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;

use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RepositoryModel extends InterfacesRepositoryEloquentInterface
{
	/**
	* @var Model $model Base Model of Repository
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
		return $this->model->delete();
	}
	/**
	* @param mixed $id
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
		return $this->model->findAll();
	}
	/**
	* @param Model $model
	* @param array $attributes
	* @return Model
	*/
	public function update(array $attributes)
	{
		return $this->model->update($attributes);
	}
	/**
	* @param Model $model
	* @return Resource
	*/
	public function getResourceModel(Model $model)
	{
		return new Resource($model);
	}


	/**
	* 
	* @return ResourceCollection
	*/
	public function getResourceCollectionModel()
	{
		return new ResourceCollection($this->model->all());
	}


}

