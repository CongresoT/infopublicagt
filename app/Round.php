<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'rounds';
    
    public function subjects(){
		return $this->belongsToMany('App\Subject', 'tracks')
			->withTimestamps();
    }

    public function numerals(){
		return $this->belongsToMany('App\Numeral', 'numeral_tracks');
    }

}
