<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RestaurantResource;
use App\Repositorys\RestaurantRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\ResResource;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

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

    public function getPagination( Request $request )
    {
        if( $request->has('filter') ){
            $category = Category::find( $request->filter );

            $data = $category->restaurant;
            return $this->returnData( 'data' , ResResource::collection( $data ), __('Succesfully'));

        }
        $data =  $this->repositry->pagination( 10 );
        return $this->returnData( 'data' , ResResource::collection( $data ), __('Succesfully'));
    }

    public function addCategory( Request $request ){

        $category   = Category::find( $request->category_id );
        $restaurant = Restaurant::find( $request->restaurant_id );

        $restaurant->categories()->save($category);

        return $this->returnData( 'data' , ResResource::make( $restaurant ), __('Succesfully'));

    }


}
