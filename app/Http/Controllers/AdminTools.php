<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
	public function calcSubjectArticle() {
		if (!$this->active)
			dd("Deactivated option");
		set_time_limit(0);
		$roundId = 2;		
		$yQuestions = [];
		$nQuestions = [];
		$qtyNumerals = 0;
		$sumNumerals = 0;
		$tracks = Track::where('round_id',$roundId)
						->get();
		foreach ($tracks as $track){
			$qtyNumerals = 0;
			$sumNumerals = 0;
			$questions = Question::where('track_id',$track->id)
									->get();
			$yQuestions = [];
			$nQuestions = [];
			$links = [];
			foreach ($questions as $question){
				if (($question->q_type == 'c')&&($question->answer != 'NA')){
					if (!isset($yQuestions[$question->indicator->numeral_id])){
						$yQuestions[$question->indicator->numeral_id] = 0;
						$nQuestions[$question->indicator->numeral_id] = 0;
						$links[$question->indicator->numeral_id] = '';
					}
					if ($question->answer == 'Y')
						$yQuestions[$question->indicator->numeral_id]++;
					if ($question->answer == 'N')
						$nQuestions[$question->indicator->numeral_id]++;
					if ($question->links != '')
						$links[$question->indicator->numeral_id] .= "</br>".$question->links;
				}
			}
			foreach($yQuestions as $key => $value){
				$rtn = RoundTrackNumeral::where('track_id',$track->id)
											->where('numeral_id', $key)
											->first();
				if(!$rtn){
					$rtn = new RoundTrackNumeral;
					$rtn->track_id = $track->id;
					$rtn->numeral_id = $key;
				}
				if ($value+$nQuestions[$key]>0) {
					$rtn->score = ($value / ($value+$nQuestions[$key]))*100;
					//exclude numeral56("buenas practicas") from the general track score.
					if ($key != 56) {
						$qtyNumerals += 1;
						$sumNumerals += ($value / ($value+$nQuestions[$key]))*100;
					}
				}
				$rtn->links = (strlen($links[$key]) > 750) ? substr($links[$key],0,750) : $links[$key];
				$rtn->save();
			}
			$track->score = 0;
			if ($qtyNumerals > 0)
				$track->score = $sumNumerals/$qtyNumerals;
			echo ("</br>".$track->subject_id." = ".$sumNumerals."/".$qtyNumerals);
			$track->save();
		}
		dd("done");
	}
	
}
