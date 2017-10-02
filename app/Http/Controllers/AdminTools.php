<?php

namespace App\Http\Controllers;

use Excel;
use File;
use App\Track;
use App\Subject;
use App\Round;
use App\Indicator;
use App\Question;
use App\Numeral;
use App\NumeralTrack;
use App\RoundTrackNumeral;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use Request;

class AdminTools extends Controller
{

	public function createTracks($round_id = null){
		//check if user has permissions
		Voyager::canOrFail('add_tracks');
		
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
		$subjects = Subject::where('enabled',True)
							->get();
		foreach($subjects as $subject){
			if (!$subject->rounds->contains($round)){
				$subject->rounds()->save($round);
			}
		}
		return redirect('admin/tracks');
		
	}

    public function mark0(){
		//check if user has permissions
		Voyager::canOrFail('edit_questions');
		
		//get the track to retrieve
        $trackId = Request::input('trackId');

        $db = DB::table('questions as q')
            ->join('indicators as i', function($join) {
                $join->on('q.indicator_id','=','i.id');
            })
            ->where('q.track_id',$trackId)
            ->where(function ($query) {
                $query->orWhere(function($query) {
                    $query->whereNull('i.parent_response');
                });
                $query->orWhere(function($query) {
                    $query->whereNotIn('i.parent_response',[True]);
                });
            })
            ->update(['q.answer' => 'N']);
            
        $db = DB::table('questions as q')
            ->join('indicators as i', function($join) {
                $join->on('q.indicator_id','=','i.id');
            })
            ->where('q.track_id',$trackId)
            ->where('i.q_type','c')
            ->where('i.parent_response',True)
            ->update(['q.answer' => 'NA']);
            
        

		return redirect('admin/trackquestions/'.$trackId);
		
	}
	
}
