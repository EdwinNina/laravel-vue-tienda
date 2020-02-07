<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $guarded = [];

    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Provider');
    }
}
