<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->resource = TagResource::class;
        $this->model = app(Tag::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( TagRequest $request ){
       
        return $this->store( $request->all() );

    }
}
