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
        $request['question'] = ['en'=>$request['question_en'],'ar'=>$request['question_ar']];
        $request['answer'] = ['en'=>$request['answer_en'],'ar'=>$request['answer_ar']];
        return $this->store( $request->except(['question_en','question_ar','answer_en','answer_ar']) );
    }

}
