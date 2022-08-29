<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\RestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Repositorys\RestaurantRepository;
use App\Traits\ResponseTrait;

class RestaurantController extends Controller
{

    use ResponseTrait;

    /**
     * @var RestaurantRepositry
     */
    protected RestaurantRepository $restaurantRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RestaurantRepository $restaurantRepositry)
    {
        $this->restaurantRepositry =  $restaurantRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = $this->restaurantRepositry->all();
        return $this->returnData('Restaurants', RestaurantResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param RestaurantRequest $request
     * @return void
     */
    public function store(RestaurantRequest $request)
    {
        $restaurant = $this->restaurantRepositry->save($request);
        

        if ($restaurant) {
            return $this->returnData('Restaurant', RestaurantResource::make($restaurant), __('Restaurant created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Restaurant!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $restaurant = $this->restaurantRepositry->getByID($id);

        if ($restaurant) {
            return $this->returnData('Restaurant', RestaurantResource::make($restaurant), __('Get Restaurant succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Restaurant!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->restaurantRepositry->delete($id);

        return $this->returnSuccessMessage(__('Delete Restaurant succesfully!'));
    }

}
