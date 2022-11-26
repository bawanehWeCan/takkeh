<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RevItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $avg = $this->points;
        if ($avg>=4 && $avg<=5) {
            $string = "خرافي";
            $icon = "5.svg";
        }elseif ($avg>=3 && $avg<=4) {
            $string = "اشي فاخر";
            $icon = "4.svg";
        }elseif ($avg>=2 && $avg<=3) {
            $string = "مرتب";
            $icon = "3.svg";
        }elseif ($avg>=1 && $avg<=2) {
            $string = "مليح";
            $icon = "2.svg";
        }elseif ($avg>=0 && $avg<=1) {
            $string = "مش بطال";
            $icon = "1.svg";
        }
        return [

            'id'=>$this->id,
            'title'=>$this->title,
            'content'=>$this->content,
            'points'=>number_format($this->points,2),
            "review_string"=>$string,
            "review_icon"=>$icon,
            'user_image'=>$this->user->image,
            'status'=>$this->status,
        ];
    }
}
