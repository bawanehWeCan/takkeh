<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $c =array('1'=>'name');
        $cc =array();
        array_push($cc,$c);
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'price'=>$this->price,
            'categorise'=>$cc,
            'sizes'=>$this->sizes,
            'extras'=>$this->extras,
        ];
    }
}
