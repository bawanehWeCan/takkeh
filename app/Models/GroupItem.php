<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupItem extends Model
{
    use HasFactory;

    public $fillable = ['name','price','group_id'];
}
