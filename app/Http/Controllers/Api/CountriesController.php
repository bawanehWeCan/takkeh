<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use PragmaRX\Countries\Package\Countries;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    use ResponseTrait;


    public function getCountries(){

        echo 'alaa';
        return;
        $allCountries = Collect(json_decode(file_get_contents(asset("countries/_all_countries.json")), true));
        return $allCountries;
        $all=[];
        foreach ($allCountries as $key => $country) {
            if(!empty($country["independent"]["name_ar"])){
                if (is_file(public_path("countries/flags/" .$key.".svg"))) {
                    $svg = file_get_contents(public_path("countries/flags/" .$key.".svg"))??null;
                }
                $data = [
                    'code'=>$country["independent"]["cca2"],
                    'name'=>$country["independent"]['name_ar'],
                    'calling_code'=>$country["independent"]['calling_codes'][0]??null,
                    'flag'=>$svg,
                ];
            }
        array_push($all,$data);
        }

        return $all;
        foreach($all as $k => $country){
            if($country['code'] == 'PS'){
                $out = array_splice($all, $k,1);
            }

        }
        return array_merge($out,$all);
    }
}
