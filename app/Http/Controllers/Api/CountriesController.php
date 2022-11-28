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

    public function getCountries()
    {
        $countries = Countries::all()
            ->filter(function ($country) {
                return $country['name_ar'] != null;
            })->map(function ($country) {

                return [
                    'code' => $country->cca2,
                    'name' => $country['name_ar'],
                    'calling_code' => $country['calling_codes'][0] ?? null,
                    'flag' => $country['flag']['svg'],
                ];
            })->values()
            ->toArray();

        foreach ($countries as $k => $country) {
            if ($country['code'] == 'PS') {
                $out = array_splice($countries, $k, 1);
            }
        }
        dd( $out );
        return $countries = array_merge($out, $countries);
    }
}
