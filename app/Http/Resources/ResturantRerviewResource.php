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
            'id'=>$this->id,
            'name'=>$this->name,
            'logo'=>$this->logo,
            'cover'=>$this->cover,
            'review_icon'=>'img/cats/burger.svg',
            'cost'=>'توصيل مجاني',
            'time'=>$this->time,
            'description'=>$this->description,
            'is_busy'=>$this->is_busy,
            'review_average'=>$this->review->avg('points'),
            'review'=>$this->review_title,
            'review_icon'=>$this->review_icon,
            'phone'=>$this?->user?->phone,
        ];
    }
}
