<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','image','phone','device_token','active','lat','long'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/users/'), $filename);
            $this->attributes['image'] =  'img/users/'.$filename;
        }
    }

    public function setCoverAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('img/users/'), $filename);
            $this->attributes['cover'] =  'img/users/'.$filename;
        }
    }


    public function get_roles()
    {
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }
    public function services(){
        return $this->hasMany(Service::class);
    }

    public function restaurant(){
        return $this->hasOne(Restaurant::class,"user_id","id");
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    public function cards(){
        return $this->hasMany(Card::class);
    }

    public function main(){
        return $this->hasMany(Restaurant::class)->first();
    }

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
