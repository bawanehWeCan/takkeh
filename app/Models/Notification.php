<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use HasFactory,HasTranslations;

    protected $guarded = [];

    public $translatable = ['title','content'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
