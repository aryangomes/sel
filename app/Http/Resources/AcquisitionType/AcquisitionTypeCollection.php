<?php

namespace App\Http\Resources\AcquisitionType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AcquisitionTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => AcquisitionTypeResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
