<?php

namespace App\Repositories;

use App\Models\AcquisitionType;
use App\Repositories\ModelRepository;


class AcquisitionTypeRepository  extends ModelRepository
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
}
