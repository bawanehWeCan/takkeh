<?php

namespace App\Http\Controllers\Api;

use App\Models\Info;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use App\Http\Requests\InfoRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\InfoResource;
use App\Http\Controllers\ApiController;
use App\Models\Restaurant;

class InfoController extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->resource = InfoResource::class;
        $this->model = app(Info::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( InfoRequest $request ){
        $resturant = Restaurant::find($request->restaurant_id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }
        return $this->store( $request->all() );

    }
}
