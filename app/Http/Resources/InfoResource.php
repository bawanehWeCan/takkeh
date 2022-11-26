<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource
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
            'address'=>$this->address,
            'phone'=>$this->phone,
            'Work_time'=>$this->Work_time,
            'delivery_time'=>$this->delivery_time,
            'minimum'=>number_format($this->minimum,2),
            'delivery_fee'=>number_format($this->delivery_fee,2),
            'sales_tax'=>number_format($this->sales_tax,2),
            'is_taxable'=>$this->is_taxable,
            'restaurant_id'=>$this->restaurant_id,
        ];
    }
}
