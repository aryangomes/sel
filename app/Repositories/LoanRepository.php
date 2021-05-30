<?php

namespace App\Repositories;

use App\Http\Resources\Loan\LoanCollection;
use App\Http\Resources\Loan\LoanResource;
use App\Repositories\Interfaces\LoanRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\Loan;

class LoanRepository extends RepositoryModel implements LoanRepositoryInterface
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

		$this->findAll();

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
