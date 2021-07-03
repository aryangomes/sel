<?php

namespace App\Repositories;

use App\Http\Resources\Lender\LenderCollection;
use App\Http\Resources\Lender\LenderResource;
use App\Models\Lender;
use App\Repositories\Interfaces\LenderRepositoryInterface;

use App\Repositories\RepositoryModel;

class LenderRepository extends RepositoryModel implements LenderRepositoryInterface
{
	/**
	 *
	 *
	 * @param Lender lenderModel
	 */
	public function __construct(Lender $lenderModel)
	{
		$this->resourceName = 'Lender';
		parent::__construct($lenderModel);
	}


	/**
	 * 
	 * @return LenderResource
	 */
	public function getResourceLenderModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new LenderCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Lender $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new LenderResource($model);
	}
}
