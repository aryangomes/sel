<?php

namespace App\Repositories;


use App\Models\Acquisition;
use App\Repositories\Interfaces\AcquisitionRepositoryInterface;
use App\Repositories\ModelRepository;


class AcquisitionRepository  extends ModelRepository implements AcquisitionRepositoryInterface
{
    /**
     *
     *
     * @param Acquisition $acquisitionModel
     */
    public function __construct(Acquisition $acquisitionModel)
    {
        parent::__construct($acquisitionModel);
    }

    /**
     * 
     * @return ResourceCollection
     */
    public function getResourceCollectionModel()
    {
        // return new AcquisitionCollection($this->findAll());
    }

    /**
     * @param AcquisitionType $model
     * @return Resource
     */
    public function getResourceModel($model)
    {
        // return new AcquisitionResource($model);
    }
}
