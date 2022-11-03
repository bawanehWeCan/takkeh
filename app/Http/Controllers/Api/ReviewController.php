<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Resources\ReviewResource;



class ReviewController extends ApiController
{
    public function __construct()
    {
        $this->resource = ReviewResource::class;
        $this->model = app(Review::class);
        $this->repositry =  new Repository($this->model);
    }

    public function index()
    {
      return $this->list();
    }


    public function show($id)
    {

        return  $this->view($id);


    }

    public function save(ReviewRequest $request ){
        return $this->store( $request->all() );
    }


    public function destroy($id){

        return $this->delete($id);
    }


    public function editRev($id,Request $request){

        return $this->update($id,$request->all());

    }

}
