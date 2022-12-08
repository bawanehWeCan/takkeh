<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory,HasTranslations;

    public $fillable = ['name','type','product_id'];
    public $translatable = ['name','type'];



    public function items(){
        return $this->hasMany(GroupItem::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
