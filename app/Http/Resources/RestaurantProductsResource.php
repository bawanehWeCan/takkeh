<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantProductsResource extends JsonResource
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
            'name'=>(string)$this->name,
            'logo'=>(string)$this->logo,
            'cover'=>(string)$this->cover,
            'cost'=>(double)$this->cost,
            'time'=>(string)$this->time,
            'is_busy'=>$this->is_busy,
            'description'=>(string)$this->description,
            'review_average'=>$this->review->avg('points'),
            'review'=>$this->review_title,
            'review_icon'=>$this->review_icon,
            "products"=>ResCatProResource::collection($this->products),
        ];
    }
}
