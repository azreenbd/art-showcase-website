<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * Model relationship
     */
    // A comment belongs to an artwork
    public function artwork()
    {
        return $this->belongsTo('App\Artwork');
    }

    // A comment belongs to a user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
