<?php

namespace App\Services;

use App\Http\Resources\Loan\LoanCollection;
use App\Http\Resources\Loan\LoanResource;
use App\Services\Interfaces\LoanServiceInterface;

use App\Services\CrudModelOperationsService;

use App\Models\Loan\Loan;

class LoanService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param Loan loanModel
	 */
	public function __construct(Loan $loanModel)
	{
		$this->resourceName = 'Loan';
		parent::__construct($loanModel);
	}


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new LoanCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Loan $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new LoanResource($model);
	}
}
