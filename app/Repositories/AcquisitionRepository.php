<?php

namespace App\Repositories;

use App\Http\Resources\Acquisition\AcquisitionCollection;
use App\Http\Resources\Acquisition\AcquisitionResource;
use App\Repositories\Interfaces\AcquisitionRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\Acquisition;

class AcquisitionRepository extends RepositoryModel implements AcquisitionRepositoryInterface
{
	/**
	 *
	 *
	 * @param Acquisition acquisitionModel
	 */
	public function __construct(Acquisition $acquisitionModel)
	{
		$this->resourceName = 'Acquisition';
		parent::__construct($acquisitionModel);
	}


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new AcquisitionCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Acquisition $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new AcquisitionResource($model);
	}
}
