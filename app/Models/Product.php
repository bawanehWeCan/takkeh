<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Product extends Model
{
    use HasFactory,HasTranslations;

    protected $guarded = [];
    public $translatable = ['name','content'];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/cats/'), $filename);
            $this->attributes['image'] =  'img/cats/'.$filename;
        }
    }

    public function extras(){
        return $this->hasMany(Extra::class);
    }

    public function sizes(){
        return $this->hasMany(Size::class);
    }

    public function groups(){
        return $this->hasMany(Group::class);
    }

    public function categories(){
        return $this->morphToMany( Category::class, 'categoryable' );
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

}
