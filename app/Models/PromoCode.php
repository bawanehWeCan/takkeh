<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    public $fillable = [
        'code',
        'total',
        'tax',
        'delivery_fee',
        'service_fee',
        'discount',
        'order_id'
    ];

    public $timestamps = true;
}
