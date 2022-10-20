<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Repository;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Request;

class ApiController extends Controller
{
    use ResponseTrait;



    public function list()
    {

        $data =  $this->repositry->all();

        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }

    public function pagination( $lenght = 10 )
    {

        $data =  $this->repositry->pagination( $lenght );

        return $this->returnData( 'data' , $this->resource::collection( $data ), __('Succesfully'));


    }


    /**
     * store function
     *
     * @param Request $request
     * @return void
     */
    protected function _save( $request )
    {
        $model = $this->repositry->save( $request );


        if ($model) {
            return $this->returnData( 'data' , $this->resource::make( $model ), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));
    }




    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        if ($model) {
            return $this->returnData('data', $this->resource::make( $model ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->repositry->deleteByID($id);

        return $this->returnSuccessMessage(__('Delete succesfully!'));
    }

    public function search($value){

        $data = $this->repositry->searchManyByKey('name',$value);

        if ($data) {
            return $this->returnData('data', $this->resource::collection( $data ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));


    }
}
