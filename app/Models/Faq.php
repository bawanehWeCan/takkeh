<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory,HasTranslations;

    public $fillable = ['question','answer'];
    public $translatable = ['question','answer'];

    public $timestamps = true;
}
