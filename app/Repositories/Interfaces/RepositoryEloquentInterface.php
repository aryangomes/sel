<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Interface RepositoryEloquentInterface
 * @package App\Repositories
 */

interface RepositoryEloquentInterface
{
	/**
	 * @param array $attributes
	 * @return Model
	 */
	public function create(array $attributes);


	/**
	 * @param Model $model
	 * @return void
	 */
	public function delete(Model $model);


	/**
	 * @param mixed $id
	 * @return Model
	 */
	public function findById($id);


	/**
	 * 
	 * @return Collection
	 */
	public function findAll();


	/**
	 * @param Model $model
	 * @param array $attributes
	 * @return Model
	 */
	public function update(array $attributes, Model $model);


	/**
	 * @param Model $model
	 * @return Resource
	 */
	public function getResourceModel(Model $model);


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel();
}
