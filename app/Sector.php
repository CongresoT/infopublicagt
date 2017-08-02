<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'sectors';
    public $timestamps = false;

    public function subjects(){
        return $this->hasMany('App\Subject');
    }
}
