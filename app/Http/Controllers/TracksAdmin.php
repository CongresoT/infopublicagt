<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBreadController as BaseVoyagerBreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use App\Round;


class TracksAdmin extends BaseVoyagerBreadController
{
    public function index(Request $request, $round_id = null)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('browse_'.$dataType->name);
		
        $getter = $dataType->server_side ? 'paginate' : 'get';

		//get the round to retrieve
		if ($round_id == null){
			$rounds = Round::where('is_done', False)
							->orderby('created_at', 'desc')
							->get();
			if ($rounds->isEmpty())
					dd("404 not found (not active rounds, create a round that is not finished first)");
			$round = $rounds->get(0);
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not found (there are not created rounds)");
		}
		
        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

			$relationships = $this->getRelationships($dataType);
			
			$dataTypeContent = call_user_func([$model->where('round_id',$round->id)->with($relationships)->orderBy('subject_id', 'asc'), $getter]);
			$dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
			
            //Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }
		
        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'round'));
    }
}
