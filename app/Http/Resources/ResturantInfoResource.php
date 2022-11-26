<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\Cast\Double;

class ResturantInfoResource extends JsonResource
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
            'cost'=>$this->cost,
            'time'=>$this->time,
            'is_busy'=>$this->is_busy,
            'description'=>$this->description,
            'review_average'=>$this->review->avg('points'),
            'review'=>$this->review_title,
            'review_icon'=>$this->review_icon,
            'address'=>$this->info->address,
            'phone'=>$this->info->phone,
            'Work_time'=>$this->info->Work_time,
            'address'=>$this->info->address,
            'delivery_time'=>$this->info->delivery_time,
            'minimum'=>$this->info->minimum,
            'delivery_fee'=>(double)$this->info->delivery_fee,
            'sales_tax'=>(double)$this->info->sales_tax,
            'is_taxable'=>$this->info->is_taxable,
            'reviews' => RevItemResource::collection($this->review)

        ];
    }
}
