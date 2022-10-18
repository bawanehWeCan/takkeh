<?php
namespace App\Helpers;



class GeoHash
{
    private $neighbors = [];
    private $borders = [];

    private $coding = "0123456789bcdefghjkmnpqrstuvwxyz";
    private $codingMap = [];

    public function __construct()
    {

        $this->neighbors['right']['even'] = 'bc01fg45238967deuvhjyznpkmstqrwx';
        $this->neighbors['left']['even'] = '238967debc01fg45kmstqrwxuvhjyznp';
        $this->neighbors['top']['even'] = 'p0r21436x8zb9dcf5h7kjnmqesgutwvy';
        $this->neighbors['bottom']['even'] = '14365h7k9dcfesgujnmqp0r2twvyx8zb';

        $this->borders['right']['even'] = 'bcfguvyz';
        $this->borders['left']['even'] = '0145hjnp';
        $this->borders['top']['even'] = 'prxz';
        $this->borders['bottom']['even'] = '028b';

        $this->neighbors['bottom']['odd'] = $this->neighbors['left']['even'];
        $this->neighbors['top']['odd'] = $this->neighbors['right']['even'];
        $this->neighbors['left']['odd'] = $this->neighbors['bottom']['even'];
        $this->neighbors['right']['odd'] = $this->neighbors['top']['even'];

        $this->borders['bottom']['odd'] = $this->borders['left']['even'];
        $this->borders['top']['odd'] = $this->borders['right']['even'];
        $this->borders['left']['odd'] = $this->borders['bottom']['even'];
        $this->borders['right']['odd'] = $this->borders['top']['even'];


        for ($i = 0; $i < 32; $i++) {
            $this->codingMap[substr($this->coding, $i, 1)] = str_pad(decbin($i), 5, "0", STR_PAD_LEFT);
        }

    }




    public function encode($lat, $long, $len = 9)
    {

        $plat = $this->precision($lat);
        $latbits = 1;
        $err = 45;
        while ($err > $plat) {
            $latbits++;
            $err /= 2;
        }


        $plong = $this->precision($long);
        $longbits = 1;
        $err = 90;
        while ($err > $plong) {
            $longbits++;
            $err /= 2;
        }


        $bits = max($latbits, $longbits);

        $longbits = $bits;
        $latbits = $bits;
        $addlong = 1;
        while (($longbits + $latbits) % 5 != 0) {
            $longbits += $addlong;
            $latbits += !$addlong;
            $addlong = !$addlong;
        }



        $blat = $this->binEncode($lat, -90, 90, $latbits);
        $blong = $this->binEncode($long, -180, 180, $longbits);


        $binary = "";
        $uselong = 1;
        while (strlen($blat) + strlen($blong)) {
            if ($uselong) {
                $binary = $binary . substr($blong, 0, 1);
                $blong = substr($blong, 1);
            } else {
                $binary = $binary . substr($blat, 0, 1);
                $blat = substr($blat, 1);
            }
            $uselong = !$uselong;
        }


        $hash = "";
        for ($i = 0; $i < strlen($binary); $i += 5) {
            $n = bindec(substr($binary, $i, 5));
            $hash = $hash . $this->coding[$n];
        }
        if ($len > 0) {
            return substr($hash, 0, $len);
        }
        return $hash;
    }



    private function precision($number)
    {
        $precision = 0;
        $pt = strpos($number, '.');
        if ($pt !== false) {
            $precision = -(strlen($number) - $pt - 1);
        }
        return pow(10, $precision) / 2;
    }

    private function binEncode($number, $min, $max, $bitcount)
    {
        if ($bitcount == 0) return "";

        $mid = ($min + $max) / 2;
        if ($number > $mid) {
            return "1" . $this->binEncode($number, $mid, $max, $bitcount - 1);
        } else {
            return "0" . $this->binEncode($number, $min, $mid, $bitcount - 1);
        }
    }

}
