<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $table = 'artists';

    public $primaryKey = 'id';
    
    /**
     * Model relationship
     */
    // One artist belongs to a user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // Artist has many artworks
    public function artworks()
    {
        return $this->hasMany('App\Artwork');
    }

    // Artist can have many followers
    public function followers()
    {
        return $this->belongsToMany('App\User', 'follows'); // model, table name
    }
}
