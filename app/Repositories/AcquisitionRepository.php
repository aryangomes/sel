<?php

namespace App\Repositories;

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
		parent::__construct($acquisitionModel);
	}
}

