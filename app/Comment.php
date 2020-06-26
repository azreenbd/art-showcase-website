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
}
