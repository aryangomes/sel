<?php

namespace App\Repositories;

use App\Http\Resources\CollectionCopy\CollectionCopyCollection;
use App\Http\Resources\CollectionCopy\CollectionCopyResource;
use App\Repositories\Interfaces\CollectionCopyRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\CollectionCopy;

class CollectionCopyRepository extends RepositoryModel implements CollectionCopyRepositoryInterface
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

		$this->findAll();

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
