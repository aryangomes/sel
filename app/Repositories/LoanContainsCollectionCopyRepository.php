<?php

namespace App\Repositories;

use App\Http\Resources\LoanContainsCollectionCopy\LoanContainsCollectionCopyCollection;
use App\Http\Resources\LoanContainsCollectionCopy\LoanContainsCollectionCopyResource;
use App\Repositories\Interfaces\LoanContainsCollectionCopyRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\LoanContainsCollectionCopy;

class LoanContainsCollectionCopyRepository extends RepositoryModel implements LoanContainsCollectionCopyRepositoryInterface
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

		$this->findAll();

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
