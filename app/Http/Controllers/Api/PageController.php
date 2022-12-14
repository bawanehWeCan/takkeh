<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ExtraResource;
use App\Repositorys\ExtraRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ExtraRequest;
use App\Http\Requests\PageRequest;
use App\Http\Resources\PageResource;
use App\Models\Extra;
use App\Models\Page;
use App\Repositorys\PageRepository;

class PageController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = PageResource::class;
        $this->model = app( Page::class );
        $this->repositry =  new PageRepository( $this->model ) ;
    }

    /**
     * @param PageRequest $request
     * @return void
     */
    public function save( PageRequest $request ){
        $request['title'] = ['en'=>$request['title_en'],'ar'=>$request['title_ar']];
        $request['content'] = ['en'=>$request['content_en'],'ar'=>$request['content_ar']];

        return $this->store( $request->except(['title_en','title_ar','content_en','content_ar']) );
    }

}
