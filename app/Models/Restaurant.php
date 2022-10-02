<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    public function categories(){
        return $this->morphToMany( Category::class, 'categoryable' );
    }

    public function setLogoAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/restaurants/'), $filename);
            $this->attributes['logo'] =  'img/restaurants/'.$filename;
        }
    }

    public function setCoverAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/restaurants/'), $filename);
            $this->attributes['cover'] =  'img/restaurants/'.$filename;
        }
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function user(){
        return $this->belongsTo(Restaurant::class);
    }
}
