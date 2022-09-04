<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResResource extends JsonResource
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
            'categories'=>'name, name2',
            'review_icon'=>'img/cats/burger.svg',
            'review'=>'اشي فاخر',
            'cost'=>'توصيل مجاني',
            'time'=>$this->time,
        ];
    }
}
