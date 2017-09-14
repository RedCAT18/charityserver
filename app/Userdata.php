<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Userdata extends Model
{
    //
    public function users(){
        return $this->belongsTo('App\User');
    }
}
