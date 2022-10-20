<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Repository;

class CategoryController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = CategoryResource::class;
        $this->model = app( Category::class );
        $this->repositry =  new Repository( $this->model ) ;
    }

    /**
     * @param CategoryRequest $request
     * @return void
     */
    public function save( CategoryRequest $request ){
        return parent::_save($request);
    }

}
