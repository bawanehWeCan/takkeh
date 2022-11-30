<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource2 extends JsonResource
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
            'id'                => $this->restaurant->id,
            'name'              => $this->name,
            'last_name'         => $this->lname,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'device_token'      => $this->device_token,
            'image'             => (string)$this->image,
        ];
    }
}
