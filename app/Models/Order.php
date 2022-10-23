<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function products(){
        return $this->hasMany(CartItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
    public function codes(){
        return $this->belongsToMany(PromoCode::class);
    }
}
