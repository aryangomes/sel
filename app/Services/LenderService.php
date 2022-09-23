<?php

namespace App\Services;

use App\Http\Resources\Lender\LenderCollection;
use App\Http\Resources\Lender\LenderResource;
use App\Models\Lender;

use App\Services\CrudModelOperationsService;

class LenderService extends CrudModelOperationsService
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
	public function getResourceCollection()
	{

		$this->getAll();

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
