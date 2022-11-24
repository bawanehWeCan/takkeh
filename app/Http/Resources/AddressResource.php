<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'city'=>$this->city,
            'region'=>$this->region,
            'street'=>$this->street,
            'building_number'=>$this->building_number,
            'floor_number'=>$this->floor_number,
            'apartment_number'=>$this->apartment_number,
            'additional_tips'=>$this->additional_tips,
            'user_id'=>$this->user_id,
            'long'=>$this->long,
            'lat'=>$this->lat,
        ];
    }
}
