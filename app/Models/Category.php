<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Category extends Model
{
    use HasFactory,HasTranslations;


    protected $guarded = [];

    public $translatable = ['name'];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/cats/'), $filename);
            $this->attributes['image'] =  'img/cats/'.$filename;
        }
    }

    public function restaurant(){
        return $this->morphedByMany( Restaurant::class, 'categoryable' );
    }
    public function Products(){
        return $this->morphedByMany( Product::class, 'categoryable' );
    }


}
