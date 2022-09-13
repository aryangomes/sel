<?php

namespace App\Services;

use App\Http\Resources\CollectionType\CollectionTypeCollection;
use App\Http\Resources\CollectionType\CollectionTypeResource;

use App\Services\CrudModelOperationsService;

use App\Models\CollectionType;

class CollectionTypeService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param CollectionType collectionTypeModel
	 */
	public function __construct(CollectionType $collectionTypeModel)
	{
		$this->resourceName = 'Collection Type';
		parent::__construct($collectionTypeModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionTypeCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionType $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionTypeResource($model);
	}
}
