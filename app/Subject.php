<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'subjects';
    

    public function sector(){
        return $this->belongsTo('App\Sector');
    }
    
    public function rounds(){
		return $this->belongsToMany('App\Round', 'tracks')
			->withTimestamps();
    }
	
	//sector() for voyager
	public function sectorId(){
		return $this->sector();
	}
    //voyager m2m for numerals
    public function numerals(){
         return $this->belongsToMany(Numeral::class, 'numerals_subjects');
    }
    
}
