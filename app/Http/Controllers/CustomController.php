<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Repositorys\RestaurantRepository;
use App\Traits\ResponseTrait;

class CustomController extends Controller
{

    use ResponseTrait;


    /**
     * Create new Library class.
     *
     * this abstraction expects the child class to have a protected attribute named model.
     * that will hold the model name with its full namespace.
     */
    public function __construct($repositry,$resource,$model)
    {
        $this->repositry =  $repositry;
        $this->resource = $resource;
        $this->model = $model;
    }
    

    public function list()
    {

        $data =  $this->repositry->all();

        return $this->returnData( 'data' , new $this->resource( $data ), __('Succesfully'));


    }

    public function pagination( $lenght = 10 )
    {

        $data =  $this->repositry->pagination( $lenght );

        return $this->returnData( 'data' , new $this->resource( $data ), __('Succesfully'));


    }


    /**
     * store function
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $model = $this->repositry->save($request);
        

        if ($model) {
            //return $this->returnData('', Resource::make($model), __(' created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));
    }



    // /**
    //  * profile function
    //  *
    //  * @param [type] $id
    //  * @return void
    //  */
    // public function profile($id)
    // {
    //     $model = $this->repositry->getByID($id);

    //     if ($model) {
    //         return $this->returnData('', Resource::make($model), __('Get  succesfully'));
    //     }

    //     return $this->returnError(__('Sorry! Failed to get !'));
    // }

    // /**
    //  * delete function
    //  *
    //  * @param [type] $id
    //  * @return void
    //  */
    // public function delete($id)
    // {
    //     $this->repositry->delete($id);

    //     return $this->returnSuccessMessage(__('Delete succesfully!'));
    // }

}
