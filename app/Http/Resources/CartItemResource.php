<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'product_id'=>$this->product->id,
            'product_name'=>$this->product->name,
            'quantity'=>$this->quantity,
            'size_id'=>$this->size_id,
            'size'=>$this->size->name,
            'extras'=> ProductItemResource::collection($this->extras) ,
            'note'=>$this->note,
            'price'=>$this->price,
        ];
    }
}
