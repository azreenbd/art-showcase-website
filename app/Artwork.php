<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    public $primaryKey = 'id';

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }
}
