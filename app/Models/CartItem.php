<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }
    // public function size(){
    //     return $this->belongsTo(Size::class);
    // }
    // public function extras(){
    //     return $this->hasMany(ProductItem::class);
    // }

    public function items(){
        return $this->hasMany( ProductItem::class , 'cart_item_id' );
    }

    public function getNameAttribute(){
        return $this->product->name;
    }
}
