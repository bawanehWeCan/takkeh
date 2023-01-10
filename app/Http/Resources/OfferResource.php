<?php

namespace App\Http\Resources;

use App\Models\Product;
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


        if ($this->offerable_type == 'App\Models\Restaurant') {

            $r = Restaurant::find($this->offerable_id);
            return [
                'id' => $this->id,
                'image' => $this->image,
                'offerable_id' => $this->offerable_id,
                'offerable_type' => $this->offerable_type,

                'restaurant_id' => $r->id,
                'title' => $r->name,

                'logo' => $r->logo,
                'cover' => $r->cover,
                'review_icon' => 'img/cats/burger.svg',
                'cost' => 'توصيل مجاني',
                'time' => $r->time,
                'description' => $r->description,
                'is_busy' => $r->is_busy,
                'review_average' => $r->review->avg('points'),
                'review' => $r->review_title,
                'review_icon' => $r->review_icon,

                'route' => 'restaurant',

            ];
        }

        $p = Product::find($this->offerable_id);

        return [
            'id' => $this->id,
            'offerable_id' => $this->offerable_id,
            'offerable_type' => $this->offerable_type,


            'restaurant_id' => $p?->r->id,
            'title' => $p?->r->name,


            'logo' => $p?->r->logo,
            'cover' => $p?->r->cover,
            'review_icon' => 'img/cats/burger.svg',
            'cost' => 'توصيل مجاني',
            'time' => $p?->r->time,
            'description' => $p?->r->description,
            'is_busy' => $p?->r->is_busy,
            'review_average' => $p?->r->review->avg('points'),
            'review' => $p?->r->review_title,
            'review_icon' => $p?->r->review_icon,

            'name' => $p->name,
            'content' => $p->content,
            'image' => $p->image,
            'is_available' => 1,
            'price' => number_format($p->price, 2),
            'categorise' => CategoryItemResource::collection($p->categories),
            'groups' => GroupResource::collection($p->groups)
        ];
    }
}
