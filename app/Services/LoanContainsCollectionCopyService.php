<?php

namespace App\Services;

use App\Http\Resources\LoanContainsCollectionCopy\LoanContainsCollectionCopyCollection;
use App\Http\Resources\LoanContainsCollectionCopy\LoanContainsCollectionCopyResource;

use App\Services\CrudModelOperationsService;

use App\Models\LoanContainsCollectionCopy;

class LoanContainsCollectionCopyService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param LoanContainsCollectionCopy loanContainsCollectionCopyModel
	 */
	public function __construct(LoanContainsCollectionCopy $loanContainsCollectionCopyModel)
	{
		$this->resourceName = 'LoanContainsCollectionCopy';
		parent::__construct($loanContainsCollectionCopyModel);
	}



	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new LoanContainsCollectionCopyCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param LoanContainsCollectionCopy $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new LoanContainsCollectionCopyResource($model);
	}
}
