<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'username', 'email', 'password',
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

    /**
     * Model relationship
     */
    // One user can have one artist profile
    public function artist()
    {
        return $this->hasOne('App\Artist');
    }

    // User can follow many artists
    public function follows()
    {
        return $this->belongsToMany('App\Artist', 'follows'); // model, table name
    }

    // User can favourite many artwork
    public function favourites()
    {
        return $this->belongsToMany('App\Artwork', 'favourites')->withTimestamps(); // model, table name
    }

    // One user can have many comments
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
