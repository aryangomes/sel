<?php

namespace App\Http\Resources\CollectionCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionCategoryResource extends JsonResource
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
