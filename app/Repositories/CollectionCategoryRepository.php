<?php

namespace App\Repositories;

use App\Http\Resources\CollectionCategoryCollection;
use App\Http\Resources\CollectionCategoryResource;
use App\Repositories\Interfaces\CollectionCategoryRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\CollectionCategory;

class CollectionCategoryRepository extends RepositoryModel implements CollectionCategoryRepositoryInterface
{
	/**
	 *
	 *
	 * @param CollectionCategory collectionCategoryModel
	 */
	public function __construct(CollectionCategory $collectionCategoryModel)
	{
		parent::__construct($collectionCategoryModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionCategoryCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionCategory $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionCategoryResource($model);
	}
}
