<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MyOrdersResource extends JsonResource
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
            'restaurant_id'=>(string)$this->restaurant_id,
            'restaurant_name'=>(string)$this->restaurant?->name,
            'restaurant_logo'=>(string)$this->restaurant?->logo,
            'order_number'=>$this->id,
            'created_at'=>$this->created_at,
        ];
    }
}
