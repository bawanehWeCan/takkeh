<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Repositorys\RestaurantRepository;
use App\Http\Controllers\CustomController;
use App\Models\Restaurant;
use App\Models\User;
use App\Traits\ResponseTrait;
use GuzzleHttp\Psr7\Request;

class RestaurantController extends CustomController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = RestaurantResource::class;
        $this->model = app( Restaurant::class );
        $this->repositry =  new RestaurantRepository( $this->model ) ;
    }

}
