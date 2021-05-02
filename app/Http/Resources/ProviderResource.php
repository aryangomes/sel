<?php

namespace App\Http\Resources;

use App\Models\JuridicPerson;
use App\Models\NaturalPerson;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $naturalPerson = NaturalPerson::where('idProvider',$this->idProvider)->first();

        $juridicPerson = JuridicPerson::where('idProvider',$this->idProvider)->first();

        return [
            'name' =>$this->name ,
            'email' =>  $this->email,
            'streetAddress' =>$this->streetAddress,
            'neighborhoodAddress' =>$this->neighborhoodAddress ,
            'numberAddress' => $this->numberAddress,
            'phoneNumber' => $this->phoneNumber,
            'cellNumber' => $this->cellNumber ,
            'complementAddress' => $this->complementAddress,
            'naturalPerson' => $this->when((isset($this->naturalPerson)),new NaturalPersonResource($naturalPerson)),
            'juridicPerson' => $this->when((isset($this->juridicPerson)),new JuridicPersonResource($juridicPerson)),
            
        ];
    }
}
