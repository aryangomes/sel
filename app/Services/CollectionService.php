<?php

namespace App\Services;

use App\Http\Resources\Collection\CollectionCollection;
use App\Http\Resources\Collection\CollectionResource;

use App\Services\CrudModelOperationsService;

use App\Models\Collection;

class CollectionService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param Collection collectionModel
	 */
	public function __construct(Collection $collectionModel)
	{
		$this->resourceName = 'Collection';
		parent::__construct($collectionModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionType $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionResource($model);
	}
}
