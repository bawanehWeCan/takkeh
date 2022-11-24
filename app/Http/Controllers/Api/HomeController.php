<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\SpecialResource;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Slider;
use App\Models\Special;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * __invoke
     *
     * @return void
     */
    public function home()
    {
        $sliders = Slider::all();
        $cats = Category::all();
        $offers = Offer::all();
        $specials = Special::all();

        return response()->json([
            'sliders'=>SliderResource::collection($sliders),
            'categories'=>CategoryResource::collection($cats),
            'offers'=>OfferResource::collection($offers),
            'specials'=>SpecialResource::collection($specials),
        ],200);
    }
}
