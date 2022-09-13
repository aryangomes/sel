<?php

namespace App\Services;

use App\Http\Resources\CollectionCategory\CollectionCategoryCollection;
use App\Http\Resources\CollectionCategory\CollectionCategoryResource;

use App\Services\CrudModelOperationsService;

use App\Models\CollectionCategory;

class CollectionCategoryService extends CrudModelOperationsService
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

		$this->getAll();

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
