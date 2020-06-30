<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    public $primaryKey = 'id';

    protected $fillable = ['title', 'description', 'filename', 'artist_id'];

    /**
     * Model relationship
     */
    // An artwork belongs to an artist
    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }

    // Artwork can have many users favourite
    public function favourites()
    {
        return $this->belongsToMany('App\User', 'favourites')->withTimestamps(); // model, table name
    }

    // An artwork can have many comments
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
