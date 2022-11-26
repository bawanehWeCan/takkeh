<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    public $guarded = [];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function setLogoAttribute($value)
    {
        if ($value) {
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename = time() . mt_rand(1000, 9999) . '.' . $extension;
            $file->move(public_path('img/restaurants/'), $filename);
            $this->attributes['logo'] =  'img/restaurants/' . $filename;
        }
    }

    public function setCoverAttribute($value)
    {
        if ($value) {
            $file = $value;
            $extension = $file->getClientOriginalExtension();
            $filename = time() . mt_rand(1000, 9999) . '.' . $extension;
            $file->move(public_path('img/restaurants/'), $filename);
            $this->attributes['cover'] =  'img/restaurants/' . $filename;
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function info()
    {
        return $this->hasOne(Info::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function review()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'tagable');
    }

    public function getReviewTitleAttribute()
    { {
            $avg = $this->review->avg('points');

            if ($avg >= 4 && $avg <= 5) {
                $resturant = "خرافي";
            } elseif ($avg >= 3 && $avg <= 4) {
                $resturant = "اشي فاخر";
            } elseif ($avg >= 2 && $avg <= 3) {
                $resturant = "مرتب";
            } elseif ($avg >= 1 && $avg <= 2) {
                $resturant = "مليح";
            } elseif ($avg >= 0 && $avg <= 1) {
                $resturant = "مش بطال";
            }
            return $resturant;
        }
    }

    public function getReviewIconAttribute()
    { {
            $avg = $this->review->avg('points');

            if ($avg >= 4 && $avg <= 5) {
                $resturant = "5.svg";
            } elseif ($avg >= 3 && $avg <= 4) {
                $resturant = "4.svg";
            } elseif ($avg >= 2 && $avg <= 3) {
                $resturant = "3.svg";
            } elseif ($avg >= 1 && $avg <= 2) {
                $resturant = "2.svg";
            } elseif ($avg >= 0 && $avg <= 1) {
                $resturant = "1.svg";
            }
            return $resturant;
        }
    }
}
