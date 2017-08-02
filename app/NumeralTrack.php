<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NumeralTrack extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'numeral_tracks';
	public $timestamps = false;

    public function round(){
        return $this->belongsTo('App\Round');
    }

    public function numeral(){
        return $this->belongsTo('App\Numeral');
    }
	
}