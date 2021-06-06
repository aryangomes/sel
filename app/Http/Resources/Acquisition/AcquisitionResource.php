<?php

namespace App\Http\Resources\Acquisition;

use Illuminate\Http\Resources\Json\JsonResource;

class AcquisitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
