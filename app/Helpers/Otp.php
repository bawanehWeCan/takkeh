<?php

namespace App\Helpers;

class WeCanOTP
{
    public static function send($phone)
    {
        $otp = mt_rand(1000,9999);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://82.212.81.40:8080/websmpp/websms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "user=Wecan&pass=Suh12345&sid=TAKKEH&mno=" . $phone . "&text=" . $otp . "&type=1&respformat=json",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 2c1d0706b21b715ff1e5a480b8360d90"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
