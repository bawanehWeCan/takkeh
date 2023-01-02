<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Restaurant;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        if( $this->offerable_type == 'App\Models\Restaurant'){

            $r = Restaurant::find($this->offerable_id);
            return [
                'id'=>$this->id,
                'image'=>$this->image,
                'offerable_id'=>$this->offerable_id,
                'offerable_type'=>$this->offerable_type,

                'restaurant_id'=>$r->id,
                'title'=>$r->name,

                'logo'=>$r->logo,
                'cover'=>$r->cover,
                'review_icon'=>'img/cats/burger.svg',
                'cost'=>'توصيل مجاني',
                'time'=>$r->time,
                'description'=>$r->description,
                'is_busy'=>$r->is_busy,
                'review_average'=>$r->review->avg('points'),
                'review'=>$r->review_title,
                'review_icon'=>$r->review_icon,

                'route'=> 'restaurant',

            ];
        }
        return [
            'id'=>$this->id,
            'image'=>$this->image,
            'offerable_id'=>$this->offerable_id,
            'offerable_type'=>$this->offerable_type,
        ];
    }
}
