<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RestaurantResource;
use App\Repositorys\RestaurantRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\ResResource;
use App\Models\Restaurant;

class RestaurantController extends ApiController
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

    /**
     * @param RestaurantRequest $request
     * @return void
     */
    public function save( RestaurantRequest $request ){
        return $this->store( $request );
    }

    public function list()
    {

        $data =  $this->repositry->all();

        return $this->returnData( 'data' , ResResource::collection( $data ), __('Succesfully'));


    }

}
