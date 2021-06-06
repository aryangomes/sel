<?php

namespace App\Repositories;

use App\Http\Resources\Rule\RuleCollection;
use App\Http\Resources\Rule\RuleResource;
use App\Repositories\Interfaces\RuleRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\Rule;

class RuleRepository extends RepositoryModel implements RuleRepositoryInterface
{
	/**
	 *
	 *
	 * @param Rule profileRuleModel
	 */
	public function __construct(Rule $profileRuleModel)
	{
		$this->resourceName = 'Rule';
		parent::__construct($profileRuleModel);
	}


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new RuleCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Rule $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new RuleResource($model);
	}
}
