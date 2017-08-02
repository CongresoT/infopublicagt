<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoundTrackNumeral extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'round_track_numerals';
	public $timestamps = false;

    public function numeral(){
        return $this->belongsTo('App\Numeral');
    }

    public function track(){
        return $this->belongsTo('App\Track');
    }
}