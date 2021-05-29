<?php

namespace App\Repositories;

use App\Http\Resources\CollectionCategory\CollectionCategoryCollection;
use App\Http\Resources\CollectionCategory\CollectionCategoryResource;
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
		$this->resourceName = 'Collection Category';
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
