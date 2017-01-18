<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    //
	public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
