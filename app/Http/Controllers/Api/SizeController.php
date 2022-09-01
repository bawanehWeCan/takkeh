<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SizeResource;
use App\Repositorys\SizeRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\SizeRequest;
use App\Models\Size;

class SizeController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = SizeResource::class;
        $this->model = app( Size::class );
        $this->repositry =  new SizeRepository( $this->model ) ;
    }

    /**
     * @param SizeRequest $request
     * @return void
     */
    public function save( SizeRequest $request ){
        return $this->store( $request );
    }

}
