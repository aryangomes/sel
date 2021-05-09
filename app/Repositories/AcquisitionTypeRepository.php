<?php

namespace App\Repositories;

use App\Http\Resources\AcquisitionType\AcquisitionTypeCollection;
use App\Http\Resources\AcquisitionType\AcquisitionTypeResource;
use App\Models\AcquisitionType;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use App\Repositories\ModelRepository;


class AcquisitionTypeRepository  extends ModelRepository implements AcquisitionTypeRepositoryInterface
{
    /**
     *
     *
     * @param AcquisitionType $acquisitionTypeModel
     */
    public function __construct(AcquisitionType $acquisitionTypeModel)
    {
        parent::__construct($acquisitionTypeModel);
    }

    /**
     * 
     * @return ResourceCollection
     */
    public function getResourceCollectionModel()
    {
        return new AcquisitionTypeCollection($this->findAll());
    }

    /**
     * @param AcquisitionType $model
     * @return Resource
     */
    public function getResourceModel($model)
    {
        return new AcquisitionTypeResource($model);
    }
}
