<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromocodeResource extends JsonResource
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
            'code'=>$this->code,
            'total'=>$this->total,
            'tax'=>$this->tax,
            'delivery_fee'=>$this->delivery_fee,
            'service_fee'=>$this->service_fee,
            'discount'=>$this->discount,
            'order_id'=>$this->order_id,
        ];
    }
}
