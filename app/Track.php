<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tracks';

    public function round(){
        return $this->belongsTo('App\Round');
    }

    public function subject(){
        return $this->belongsTo('App\Subject');
    }
	
    public function indicators(){
		return $this->belongsToMany('App\Indicator', 'questions')
			->withTimestamps();
    }

    public function numerals(){
		return $this->belongsToMany('App\Numeral', 'round_track_numerals');
    }
	
	public function questions(){
		return $this->hasMany('App\Question');
	}
}