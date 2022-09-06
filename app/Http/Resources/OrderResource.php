<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'driver_id'=>$this->user_id,
            'user_name'=>$this->user->name,
            'driver_name'=>$this->user->name,
            'restaurant_id'=>$this->restaurant_id,
            'restaurant_name'=>$this->restaurant?->name,
            'note'=>$this->note,
            'status'=>$this->status,
            'total'=>$this->total,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'products'=> CartItemResource::collection($this->products),
        ];
    }
}
