<?php

namespace App\Services;

use App\Http\Resources\CollectionCopy\CollectionCopyCollection;
use App\Http\Resources\CollectionCopy\CollectionCopyResource;

use App\Services\CrudModelOperationsService;

use App\Models\CollectionCopy;

class CollectionCopyService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param CollectionCopy collectionCopyModel
	 */
	public function __construct(CollectionCopy $collectionCopyModel)
	{
		$this->resourceName = 'Collection Copy';
		parent::__construct($collectionCopyModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionCopyCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionType $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionCopyResource($model);
	}
}
