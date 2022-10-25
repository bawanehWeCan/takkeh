<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'code',
        'type',
        'value',
    ];

    public $timestamps = true;

    protected $table = 'promo_code';

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
