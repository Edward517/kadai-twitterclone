<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
     public function likings()
    {
        return $this->belongsToMany(Micropost::class, 'user_like', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    public function like($micropostId)
{
    $exist = $this->is_liking($micropostId);
   
    if ($exist) {
        return false;
    } else {
        $this->likings()->attach($micropostId);
        return true;
    }
}

public function unlike($micropostId)
{
    $exist = $this->is_liking($micropostId);

    if ($exist) {
        $this->likings()->detach($micropostId);
        return true;
    } else {
        return false;
    }
}


public function is_liking($micropostId) {
    return $this->likings()->where('micropost_id', $micropostId)->exists();
}
}

