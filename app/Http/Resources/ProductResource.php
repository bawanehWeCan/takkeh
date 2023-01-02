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

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'content'=>$this->content,
            'image'=>$this->image,
            'is_available'=>1,
            'price'=>number_format($this->price,2),
            'categorise'=>CategoryItemResource::collection($this->categories),
            'groups' => GroupResource::collection($this->groups)
        ];
    }
}
