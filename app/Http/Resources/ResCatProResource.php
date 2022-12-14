<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResCatProResource extends JsonResource
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
            'image'=>$this->image,
            'sold_quantity'=>$this->sold_quantity,
            'description'=>$this?->content,
            'price'=>number_format($this->price,2),
            // 'groups' => GroupResource::collection($this?->groups)
        ];
    }
}
