<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Resources\ResResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RestaurantRequest;
use App\Repositorys\RestaurantRepository;
use App\Http\Resources\RestaurantResource;

class RestaurantController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = ResResource::class;
        $this->model = app( Restaurant::class );
        $this->repositry =  new Repository( $this->model ) ;
    }

    /**
     * @param RestaurantRequest $request
     * @return void
     */
    public function save( RestaurantRequest $request ){
        return $this->store( $request->all() );
    }

    public function getPagination( Request $request )
    {
        if( $request->has('filter') ){
            $category = Category::find( $request->filter );

            $data = $category->restaurant;
            return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));

        }
        $data =  $this->repositry->pagination( 10 );
        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));
    }

    public function addCategory( Request $request ){

        $category   = Category::find( $request->category_id );
        $restaurant = Restaurant::find( $request->restaurant_id );

        $restaurant->categories()->save($category);

        return $this->returnData( 'data' , $this->resource::make( $restaurant ), __('Succesfully'));

    }
    public function lookfor(Request $request){

        return $this->search('name',$request->keyword);

    }

    /**
     * addReviewToResturant
     *
     * @param  mixed $request
     * @return void
     */
    public function addReviewToResturant(Request $request)
    {

        $user = User::find($request->user_id);

        $resturant = Restaurant::where('user_id', $request->user_id)->find($request->resturant_id);
        if (!$resturant || !empty($resturant->review)) {
            return $this->returnError(__('Error! something has been wrong'));
        }

        $request['reviewable_id'] = $request->resturant_id;
        $request['reviewable_type'] = get_class($resturant);
        unset($request['resturant_id']);
        $resturant_with_review = $resturant->review()->create($request->all());

        $resturant->review = $resturant_with_review;
        return $this->returnData('data', new $this->resource($resturant), '');
    }

    public function resturantWithProducts($id)
    {
        $resturant = $this->repositry->getByID($id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }
        return $this->returnData('data', new RestaurantResource($resturant), '');
    }

}
