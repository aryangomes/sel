<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LenderResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'streetAddress' => $this->streetAddress,
            'neighborhoodAddress' => $this->neighborhoodAddress,
            'numberAddress' => $this->numberAddress,
            'phoneNumber' => $this->phoneNumber,
            'cellNumber' => $this->cellNumber,
            'complementAddress' => $this->complementAddress,
            'site' => $this->site
        ];
    }
}
