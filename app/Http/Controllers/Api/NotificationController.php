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

        return $this->store($request->all());
    }


    function send()
    {
        $token = "d1VC8jmZRL-4maUWM2che4:APA91bFQMZHwu38Tn-0kHMVAoCF0do1Uh2DB-dOSolvHU7977XWVmXCZ1zBiTyLekyHcCnXQMbrev9QYg6PBGQnLSDEd2xhOjpJomeEMn_nFJgZH0jv5Lh3LuIDm6W0PVe7FnNc6uSkR";
        $from = "AAAA53N59_0:APA91bF27-YuWlYtfLoFYPDzsTqzL7VZvkTD5VsU3Gz9kh2thHQrn0HLm3lG4oedlPBNsqR61th11zgCkb_zHuJwm96oZzm0UBkHItKKgkcYF5YvawN32URY2lZTLMg8GcYMmccdDJC8";
        $msg = array(
            'body'  => "Testing Testing",
            'title' => "Hi, From Wecan",
            'receiver' => 'Alaa',
        );

        $fields = array(
            'to'        => $token,
            'notification'  => $msg

        );


        $headers = array(
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        dd($result);
        curl_close($ch);
    }
}
