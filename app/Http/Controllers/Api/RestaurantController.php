<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\ProductItem;
use App\Models\Categoryable;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Resources\ResResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CatProResource;
use App\Http\Controllers\ApiController;
use App\Http\Resources\RevItemResource;
use App\Http\Requests\RestaurantRequest;
use App\Repositorys\RestaurantRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\RestCatProResource;
use App\Http\Resources\ProductItemResource;
use App\Http\Resources\CategoryItemResource;
use App\Http\Resources\ResturantInfoResource;
use App\Http\Resources\ResturantrevsResource;
use App\Http\Resources\ResturantRerviewResource;
use App\Http\Resources\RestaurantProductsResource;
use App\Models\Product;

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
        $data =  $this->repositry->pagination(10);
        if (!isset($request->category_id) && !isset($request->tag_id)) {
            $data =  $this->repositry->pagination(10);
        }elseif (isset($request->category_id) && isset($request->tag_id)) {
            $data =  $this->model->with('review')->whereHas('categories',function(Builder $q) use ($request){
                $q->where('category_id',$request->category_id);
            })->whereHas('tags',function(Builder $q) use ($request){
                $q->where('tag_id',$request->tag_id);
            })->paginate( 10 );
        }elseif (isset($request->category_id) && !isset($request->tag_id)) {
            $data =  $this->model->with('review')->whereHas('categories',function(Builder $q) use ($request){
                $q->where('category_id',$request->category_id);
            })->paginate( 10 );
        }elseif (isset($request->tag_id) && !isset($request->category_id)) {
            $data =  $this->model->with('review')->whereHas('tags',function(Builder $q) use ($request){
                $q->where('tag_id',$request->tag_id);
            })->paginate( 10 );
        }

        // return json_encode($all);
        return $this->returnData('data', ResturantRerviewResource::collection($data), '');    }

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

        $resturant = Restaurant::find($request->resturant_id);
        if (!$resturant ) {
            return $this->returnError(__('Error! something has been wrong'));
        }

        $request['reviewable_id'] = $request->resturant_id;
        $request['reviewable_type'] = get_class($resturant);
        unset($request['resturant_id']);
        $resturant_with_review = $resturant->review()->create($request->all());

        // $resturant->review = $resturant_with_review;
        return $this->returnData('data', new $this->resource($resturant), '');
    }

    public function resturantWithProducts($id)
    {
        $resturant = $this->repositry->getByID($id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }

        return $this->returnData('categories', CatProResource::collection($resturant->categories), '');
    }

    public function list_reviews($length = 10){
        $resturants = Restaurant::with('review')->paginate($length);
        // return json_encode($all);
        return $this->returnData('data', ResturantRerviewResource::collection($resturants), '');
    }

    public function updateAvailability(Request $request)
    {
        $resturant = $this->repositry->getByID($request->restaurant_id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }
        if ($resturant->is_busy == 0) {
            $resturant->update(['is_busy'=>1]);
            return $this->returnSuccessMessage('Resturant is busy now');
        }else{
            $resturant->update(['is_busy'=>0]);
            return $this->returnSuccessMessage('Resturant is ready for orders');
        }
    }

    public function addTags(Request $request)
    {
        $restaurant = $this->repositry->getByID($request->restaurant_id);
        $tags = Tag::whereIn('id',$request->tags)->get();
        if (!$restaurant) {
            return $this->returnError('This resturant is not exists');
        }

        $new = $restaurant->tags()->attach($tags);

     return $this->returnSuccessMessage('Tags added to resturant successfully');

    }

    public function get_info($id){
        $resturant = Restaurant::with(['review','info'])->find($id);
        // $resturant = $this->review_string_icon($resturant);
        return $this->returnData('data', ResturantInfoResource::make($resturant), '');
    }

    public function get_reviews($id){
        $restaurant = Restaurant::find($id);
        // $resturant = $this->review_string_icon($restaurant);

        return $this->returnData('data', ResturantInfoResource::make($restaurant));


        // return response([
        //     'restaurant'=> ResturantrevsResource::make(collect($resturant)),
        //     'reviews'=> RevItemResource::collection($restaurant->review),
        // ]);
    }

    public function searchProduct(Request $request)
    {
        $data =  $this->model->with(['products'=>function( $q) use ($request){
            $q->where('name',"like","%".$request->key."%");
        }])->find($request->restaurant_id);
        return $this->returnData('data', new RestaurantProductsResource($data), '');
    }

    public function mostPopularProducts($id)
    {
        $data =  $this->model->with(['products'=>function( $q){
            $q->orderBy('sold_quantity',"DESC")->limit(5);
        }])->find($id);
        return $this->returnData('retaurant', RestaurantProductsResource::make($data), '');
    }
}
