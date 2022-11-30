<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OController extends ApiController
{
    public function __construct()
    {
        $this->resource = OrderResource::class;
        $this->model = app(Order::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save( Request $request ){
        return $this->store( $request->all() );
    }

}
