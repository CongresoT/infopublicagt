<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBreadController as BaseVoyagerBreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use App\Round;


class RoundsAdmin extends BaseVoyagerBreadController
{
    public function showdd(Request $request){
        dd("esto");
    }
    
    public function update(Request $request, $id) {
        dd("save");
    }

}
