<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait NotificationTrait
{
    function testSend()
    {
        $token = "cxghgC8ZSWyCRuXu0Rmj9G:APA91bE8cl9Upb07x4Asdo4TNuH4KbfZtNDzNmmdWB1kxV95SQ5W8eZHvyPmr0DrD-PlIV6Man3oFJuHF6wUk8_msUSL8owEePg_dbX3GKxZCnZPyMB43JTDbeg2h3DB1NGlB_1bSbar";
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
