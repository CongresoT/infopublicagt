<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NumeralSubject extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'numerals_subjects';
	public $timestamps = false;
	
    public function numeral(){
        return $this->belongsTo('App\Numeral');
    }

    public function subject(){
        return $this->belongsTo('App\Subject');
    }

}
