<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'indicators';

    public function numeral(){
        return $this->belongsTo('App\Numeral');
    }
	
    public function tracks(){
		return $this->belongsToMany('App\Track', 'questions')
			->withTimestamps();
    }
	
	public function questions(){
		return $this->hasMany('App\Question');
	}

	//as function article() but to be used in voyager views
	public function numeralId(){
		return $this->numeral();
	}

}
