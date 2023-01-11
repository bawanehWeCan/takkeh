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

                'restaurant'=> new ResturantInfoResource( $r ),

                'route' => 'restaurant',

            ];
        }

        $p = Product::find($this->offerable_id);
        // dd( $p );
        return [
            'id' => $this->id,
            'offerable_id' => $this->offerable_id,
            'offerable_type' => $this->offerable_type,


            'restaurant_id' => $p?->restaurant->id,
            'restaurant'=> new ResturantInfoResource( $p?->restaurant ),
            'route'=> 'restaurant_product',


            'product'=> new ProductResource( $p )
        ];
    }
}
