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

            'name'=>(string)$this->name,
            'logo'=>(string)$this->logo,
            'cover'=>(string)$this->cover,
            'cost'=>(double)$this->cost,
            'time'=>(string)$this->time,
            'is_busy'=>$this->is_busy,
            'description'=>(string)$this->description,
            'review_average'=>$this->review->avg('points'),
            'review'=>$this->review_title,
            'review_icon'=>$this->review_icon,
            'address'=>(string)$this?->info?->address,
            'phone'=>(string)$this?->info?->phone,
            'Work_time'=>(string)$this?->info?->Work_time,
            'delivery_time'=>(string)$this->info?->delivery_time,
            'minimum'=>(double)$this?->info?->minimum,
            'delivery_fee'=>(double)$this?->info?->delivery_fee,
            'sales_tax'=>(double)$this?->info?->sales_tax,
            'is_taxable'=>$this?->info?->is_taxable,
            'reviews' => RevItemResource::collection($this?->review)

        ];
    }
}
