<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;

class NotificationController extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->resource = NotificationResource::class;
        $this->model = app(Notification::class);
        $this->repositry =  new Repository($this->model);
    }

    public function save(NotificationRequest $request)
    {

        $request['title'] = ['en'=>$request['title_en'],'ar'=>$request['title_ar']];
        $request['content'] = ['en'=>$request['content_en'],'ar'=>$request['content_ar']];

        return $this->store( $request->except(['title_en','title_ar','content_en','content_ar']) );
    }



}
