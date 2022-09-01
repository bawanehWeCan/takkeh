<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ExtraResource;
use App\Repositorys\ExtraRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ExtraRequest;
use App\Models\Extra;

class ExtraController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = ExtraResource::class;
        $this->model = app( Extra::class );
        $this->repositry =  new ExtraRepository( $this->model ) ;
    }

    /**
     * @param ExtraRequest $request
     * @return void
     */
    public function save( ExtraRequest $request ){
        return $this->store( $request );
    }

}
