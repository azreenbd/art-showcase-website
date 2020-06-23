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
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
