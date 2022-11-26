<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'logo'=>$this->logo,
            'cover'=>$this->cover,
            'time'=>$this->time,
            'is_busy'=>$this->is_busy,
            'description'=>$this->description,
            'cost'=>(double)$this->cost,
            "reviews"=>ReviewResource::collection($this->review),
        ];
    }


}
