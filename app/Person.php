<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    
    protected $guarded = [];

    public function proveedor()
    {
        return $this->hasOne('App\Provider');
    }
}
