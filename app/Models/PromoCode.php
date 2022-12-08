<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class PromoCode extends Model
{
    use HasFactory,HasTranslations;
    public $fillable = [
        'name',
        'code',
        'type',
        'value',
    ];

    public $translatable = ['name'];
    public $timestamps = true;

    protected $table = 'promo_code';

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
