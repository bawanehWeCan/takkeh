<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResturantInfoResource extends JsonResource
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
            'id'=>$this['id'],
            'name'=>$this['name'],
            'logo'=>$this['logo'],
            'cover'=>$this['cover'],
            'review_icon'=>'img/cats/burger.svg',
            'cost'=>'توصيل مجاني',
            'time'=>$this['time'],
            'is_busy'=>$this['is_busy'],
            'description'=>$this['description'],
            'review_average'=>$this['avg'],
            'review'=>$this['review'],
            'review_icon'=>$this['icon'],
            'address'=>$this['info']['address'],
            'phone'=>$this['info']['phone'],
            'Work_time'=>$this['info']['Work_time'],
            'delivery_time'=>$this['info']['delivery_time'],
            'minimum'=>number_format($this['info']['minimum'],2),
            'delivery_fee'=>number_format($this['info']['delivery_fee'],2),
            'sales_tax'=>number_format($this['info']['sales_tax'],2),
            'is_taxable'=>$this['info']['is_taxable'],
        ];
    }
}
