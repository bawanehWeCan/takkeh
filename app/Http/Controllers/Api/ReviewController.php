<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;
use App\Http\Controllers\ApiController;


class ReviewController extends ApiController
{
    public function __construct()
    {
        $this->resource = ReviewResource::class;
        $this->model = app(Review::class);
        $this->repositry =  new Repository($this->model);
    }


    public function show($id)
    {

        return  $this->view($id);


    }

    public function save(ReviewRequest $request ){
        $request['user_id'] = Auth::user()->id;
        return $this->store( $request->all() );
    }


    public function destroy($id){

        return $this->delete($id);
    }


    public function editRev($id,Request $request){
        $request['user_id'] = Auth::user()->id;

        return $this->update($id,$request->all());

    }

}
