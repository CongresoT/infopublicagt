<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'articles';
    public $timestamps = false;

    public function numerals(){
        return $this->hasMany('App\Numeral');
    }
}