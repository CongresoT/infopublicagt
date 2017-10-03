<?php

namespace App\Http\Controllers;

use TCG\Voyager\Http\Controllers\VoyagerBreadController as BaseVoyagerBreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use App\Track;
use App\NumeralSubject;
use App\Indicator;


class QuestionsAdmin extends BaseVoyagerBreadController
{
    public function index(Request $request, $track_id = null)
    {
		
        $slug = 'questions';

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('browse_'.$dataType->name);
		
        $getter = $dataType->server_side ? 'paginate' : 'get';

		//get the track to retrieve
		if ($track_id == null){
			dd("404 not found (select track first)");
		}
		else{
			$track = Track::find($track_id);
			if (!$track)
				dd("404 Not found (track does not exists)");
		}
		//get the indicators that should be inserted and are not inserted yet
		$applicableIndicators = DB::table('numerals_subjects as ns')
                                    ->join('indicators as i', function($join) {
                                        $join->on('i.numeral_id','=','ns.numeral_id');
                                    })
                                    ->join('tracks as t', function($join) {
                                        $join->on('t.subject_id','=','ns.subject_id');
                                    })
                                    ->leftJoin('questions as q', function($join) {
                                        $join->on('q.track_id','=','t.id');
                                        $join->on('q.indicator_id','=','i.id');
                                    })
                                    ->where('ns.subject_id', $track->subject_id)
                                    ->where('t.round_id', $track->round_id)
                                    ->where('q.id',Null)
                                    ->where('i.enabled',True)
                                    ->select('ns.numeral_id as numeral_id', 'i.id as indicator_id', 'i.q_type as indicator_q_type', 't.id as track_id', 'q.id as question_id')
                                    ->get();
		//get an array to pass to the insert method
        $insertIndicators = [];
		foreach($applicableIndicators as $ai){
            $insertIndicators[] = (['track_id' => $ai->track_id, 
                                    'indicator_id' => $ai->indicator_id, 
                                    'q_type' => $ai->indicator_q_type,
                                    'created_at' =>  \Carbon\Carbon::now(), # \Datetime()
                                    'updated_at' => \Carbon\Carbon::now(),  # \Datetime()
                                ]);
		}
        //if there is something to insert, insert it on the DB
        if (sizeof($insertIndicators)>0) {
            DB::table('questions')->insert($insertIndicators);
        }
        //get the questions that should be deleted because numeral does not apply
		$deletableQuestions = DB::table('questions as q')
                                ->join('tracks as t', function($join) {
                                    $join->on('t.id','=','q.track_id');
                                })
                                ->join('indicators as i', function($join) {
                                    $join->on('i.id','=','q.indicator_id');
                                })
                                ->join('numerals as n', function($join) {
                                    $join->on('n.id','=','i.numeral_id');
                                })
                                ->leftJoin('numerals_subjects as ns', function($join) {
                                    $join->on('ns.numeral_id','=','n.id');
                                    $join->on('ns.subject_id','=','t.subject_id');
                                })
                                ->where('t.subject_id', $track->subject_id)
                                ->where('t.round_id', $track->round_id)
                                ->where('ns.numeral_id',Null)
                                ->select('q.id as question_id');
        //get the questions that should be deleted because indicator has been disabled AND union it to $deletableQuestions
        $deletableQuestionsUnion = DB::table('questions as q')
                                        ->join('indicators as i', function($join) {
                                            $join->on('i.id','=','q.indicator_id');
                                        })
                                        ->where('i.enabled',False)
                                        ->where('q.track_id',$track->id)
                                        ->union($deletableQuestions)
                                        ->select('q.id as question_id')
                                        ->get();
        //delete if needed
        if(sizeof($deletableQuestionsUnion)>0) {
            $query = DB::table('questions');
            foreach($deletableQuestionsUnion as $dq){
                $query->orWhere('id',$dq->question_id);
            }
            $query->delete();
        }

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

			$relationships = $this->getRelationships($dataType);
			
			$dataTypeContent = call_user_func([$model->where('track_id',$track->id)->with($relationships)->orderBy('indicator_id', 'asc'), $getter]);
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
        
        return view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'track'));
    }
    
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        Voyager::canOrFail('edit_'.$dataType->name);

        //Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            //if question is parent, retrieve questions that have that question as parent and set the NA to the corresponding ones (dependig if Y or N)
            //the other corresponding should have Null value.  All of this, only if user changed answer, if not, leave the same
            if($data['attributes']['q_type'] == 'p'){
                //check if user changed answer in order to proceed
                if ($data['attributes']['answer'] != $request->request->get('answer')) {
                    //exclude parent questions with NA answer
                    if(($request->request->get('answer') == 'Y')||($request->request->get('answer') == 'N')) {
                        $answer = $request->request->get('answer')=='Y'?True:False;
                        $naChilds = DB::table('indicators as i')
                                        ->join('questions as q', function($join) {
                                            $join->on('q.indicator_id','=','i.id');
                                        })
                                        ->where('i.indicator_id', $data['attributes']['indicator_id'])
                                        ->where('q.track_id', $data['attributes']['track_id'])
                                        ->where('i.parent_response',!$answer)
                                        ->select('q.id as id')
                                        ->get();
                        if (sizeof($naChilds)>0) {
                            $query = DB::table('questions');
                            foreach($naChilds as $c){
                                $query->orWhere('id', $c->id);
                            }
                            $query->update(['answer' => 'NA']);
                        }
                        $nullChilds = DB::table('indicators as i')
                                        ->join('questions as q', function($join) {
                                            $join->on('q.indicator_id','=','i.id');
                                        })
                                        ->where('i.indicator_id', $data['attributes']['indicator_id'])
                                        ->where('q.track_id', $data['attributes']['track_id'])
                                        ->where('i.parent_response',$answer)
                                        ->select('q.id as id')
                                        ->get();
                        if (sizeof($nullChilds)>0) {
                            $query = DB::table('questions');
                            foreach($nullChilds as $c){
                                $query->orWhere('id', $c->id);
                            }
                            $query->update(['answer' => Null]);
                        }
                    }
                }
            }

            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);
            
            //search next question to redirect to that one.
            $questionnaire = DB::table('numerals_subjects as ns')
                                    ->join('indicators as i', function($join) {
                                        $join->on('i.numeral_id','=','ns.numeral_id');
                                    })
                                    ->join('tracks as t', function($join) {
                                        $join->on('t.subject_id','=','ns.subject_id');
                                    })
                                    ->leftJoin('questions as q', function($join) {
                                        $join->on('q.track_id','=','t.id');
                                        $join->on('q.indicator_id','=','i.id');
                                    })
                                    ->where('t.id', $data['attributes']['track_id'])
                                    ->where('q.answer',Null)
                                    ->orWhere('q.id',$id)
                                    ->orderby('ns.numeral_id','asc')
                                    ->orderby('i.id','asc')
                                    ->select('q.id')
                                    ->get();
            //search the it to get the next 
            $next = 0;
            foreach($questionnaire as $q){
                //find the current, assign it to next, so when $next has the value of the current, the next iteration will be the searched
                if($next == $id){
                    $next = $q->id;
                    break;
                }
                if($q->id == $id)
                    $next = $id;
            }
            if(($next == 0)||($next == $id)) {
                //it was the last one, or for some reason did not find it
                //redirect it to the view of the trackquestions
                return redirect(url("/admin/trackquestions", ['track_id' => $data['attributes']['track_id']]))
                ->with([
                    'message'    => "Successfully Updated {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                    ]);
            }
            else {
                //if it find it, redirect it to the next question
                return redirect()
                ->route("voyager.{$dataType->slug}.edit", ['id' => $next])
                ->with([
                    'message'    => "Successfully Updated {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                    ]);
            }
            
        }
    }
}
