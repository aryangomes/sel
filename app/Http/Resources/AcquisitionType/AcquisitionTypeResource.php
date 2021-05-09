<?php

namespace App\Http\Resources\AcquisitionType;

use Illuminate\Http\Resources\Json\JsonResource;

class AcquisitionTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'idAcquisitionType' => $this->idAcquisitionType,
            'type' => $this->type,
        ];
    }
}
