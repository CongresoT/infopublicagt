<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Numeral extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'numerals';
    public $timestamps = false;
    
    public function indicators(){
        return $this->hasMany('App\Indicator');
    }
    
    public function article(){
        return $this->belongsTo('App\Article');
    }
	
    public function rounds(){
		return $this->belongsToMany('App\Round', 'numeral_tracks');
    }
	
    public function tracks(){
		return $this->belongsToMany('App\Track', 'round_track_numerals');
    }

}
