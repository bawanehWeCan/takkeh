<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'title'=>$this->title,
            'content'=>$this->content,
            'points'=>number_format($this->points,2),
            'user'=>new UserResource($this->user),
            'status'=>$this->status,
        ];
    }
}
