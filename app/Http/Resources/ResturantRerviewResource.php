<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResturantRerviewResource extends JsonResource
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
        ];
    }
}
