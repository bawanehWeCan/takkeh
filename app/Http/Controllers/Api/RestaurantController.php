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
use App\Http\Requests\RestaurantRequest;
use App\Repositorys\RestaurantRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\RestCatProResource;
use App\Http\Resources\ProductItemResource;
use App\Http\Resources\CategoryItemResource;
use App\Http\Resources\ResturantInfoResource;
use App\Http\Resources\ResturantRerviewResource;

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
        if (!isset($request->category_id) && !isset($request->tag_id)) {
            $data =  $this->repositry->pagination(10);
        }elseif (isset($request->category_id) && isset($request->tag_id)) {
            $data =  $this->model->whereHas('categories',function(Builder $q) use ($request){
                $q->where('category_id',$request->category_id);
            })->whereHas('tags',function(Builder $q) use ($request){
                $q->where('tag_id',$request->tag_id);
            })->paginate( 10 );
        }elseif (isset($request->category_id) && !isset($request->tag_id)) {
            $data =  $this->model->whereHas('categories',function(Builder $q) use ($request){
                $q->where('category_id',$request->category_id);
            })->paginate( 10 );
        }elseif (isset($request->tag_id) && !isset($request->category_id)) {
            $data =  $this->model->whereHas('tags',function(Builder $q) use ($request){
                $q->where('tag_id',$request->tag_id);
            })->paginate( 10 );
        }
        return $this->returnData( 'data' , RestaurantResource::collection( $data ), __('Succesfully'));
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
        $categryables = Categoryable::where('categoryable_type',"App\Models\Product")->pluck('category_id');
        $cats = Category::with('products')->WhereIn('id',$categryables->toArray())->whereHas('products',function (Builder $q) use($resturant){
            $q->where('restaurant_id',$resturant->id);
        })->get();
        return $this->returnData('categories', CatProResource::collection($cats), '');
    }

    public function list_reviews($length = 10){
        $resturants = Restaurant::with('review')->paginate($length);
        $all=[];
        foreach ($resturants as $resturant) {
            $resturant = $this->review_string_icon($resturant);
            $all = collect($all)->push($resturant);
        }
        // return json_encode($all);
        return $this->returnData('data', ResturantRerviewResource::collection(collect($all)), '');
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
        $resturant = $this->review_string_icon($resturant);
        return $this->returnData('data', ResturantInfoResource::make(collect($resturant)), '');
    }

    public function review_string_icon($resturant)
    {
        $avg = $resturant->review->avg('points');
        $resturant = collect($resturant)->put('avg',$avg);
        if ($avg>=4 && $avg<=5) {
            $resturant = collect($resturant)->put('review',"خرافي");
            $resturant = collect($resturant)->put('icon',"5.svg");
        }elseif ($avg>=3 && $avg<=4) {
            $resturant = collect($resturant)->put('review',"اشي فاخر");
            $resturant = collect($resturant)->put('icon',"4.svg");
        }elseif ($avg>=2 && $avg<=3) {
            $resturant = collect($resturant)->put('review',"مرتب");
            $resturant = collect($resturant)->put('icon',"3.svg");
        }elseif ($avg>=1 && $avg<=2) {
            $resturant = collect($resturant)->put('review',"مليح");
            $resturant = collect($resturant)->put('icon',"2.svg");
        }elseif ($avg>=0 && $avg<=1) {
            $resturant = collect($resturant)->put('review',"مش بطال");
            $resturant = collect($resturant)->put('icon',"1.svg");
        }
        return $resturant;
    }
}
