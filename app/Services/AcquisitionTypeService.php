<?php

namespace App\Services;

use App\Http\Resources\AcquisitionType\AcquisitionTypeCollection;
use App\Http\Resources\AcquisitionType\AcquisitionTypeResource;
use App\Models\AcquisitionType;
use App\Services\CrudModelOperationsService;


class AcquisitionTypeService  extends CrudModelOperationsService
{
    /**
     *
     *
     * @param AcquisitionType $acquisitionTypeModel
     */
    public function __construct(AcquisitionType $acquisitionTypeModel)
    {
        $this->resourceName = 'Acquisition Type';
        parent::__construct($acquisitionTypeModel);
    }

    /**
     * 
     * @return ResourceCollection
     */
    public function getResourceCollectionModel()
    {

        $this->getAll();

        if ($this->transactionIsSuccessfully) {

            $this->responseFromTransaction = new AcquisitionTypeCollection($this->responseFromTransaction);
        }
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
