<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Person','person_id');

    }
}
