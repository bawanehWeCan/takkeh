<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class FaqController extends ApiController
{
    public function __construct()
    {
        $this->resource = FaqResource::class;
        $this->model = app(Faq::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( FaqRequest $request ){
        return $this->store( $request->all() );
    }

}
