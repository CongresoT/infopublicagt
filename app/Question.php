<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'questions';

	public function indicator(){
        return $this->belongsTo('App\Indicator');
    }

	public function indicatorId(){
		return $this->indicator();
	}
	
}