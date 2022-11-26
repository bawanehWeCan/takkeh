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
            'review_icon'=>$this->review_icon,
            'cost'=>(double)$this->cost,
            'time'=>$this->time,
            'is_busy'=>$this->is_busy,
            'description'=>$this->description,
            'categories'=> CategoryItemResource::collection($this?->categories),
            'tags'=> TagResource::collection($this?->tags),
            "reviews"=>ReviewResource::collection($this->review),
        ];
    }
}
