<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = true;

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/tags/'), $filename);
            $this->attributes['image'] =  'img/tags/'.$filename;
        }
    }
}
