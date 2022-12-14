<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupItem extends Model
{
    use HasFactory,HasTranslations;

    public $fillable = ['name','price','group_id'];
    public $translatable = ['name'];


    public function group(){
        return $this->belongsTo(Group::class);
    }

}
