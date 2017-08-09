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

class Load extends Controller
{

	private $active = True;
    public function excel()
    {
		if (!$this->active)
			dd("Deactivated option");
		set_time_limit(0);
        $directory = storage_path('app/public');
        $files = File::allFiles($directory);
        $htmlResult = '';
		//manually specify the round that is being worked on
		$round_id = 2;
		$round = Round::find($round_id);
		//retrieve all files in $directory
        foreach ($files as $file)
        {
			//get the subject id from the filename
			$pattern = "/([0-9]+)_([0-9]+).xlsx/";
			preg_match($pattern, $file->getFilename(), $matches);
			$subject_id = $matches[2];
			$subject = Subject::find($subject_id);
			echo "</br>SO: ".$subject->name." ".$subject_id;
			//save the track for the round - subject (if not exists)
			if (!$subject->rounds->contains($round)){
				$subject->rounds()->save($round);
			}
			$track = Track::where('subject_id', '=', $subject_id)
							->where('round_id', '=', $round_id)
							->first();
			//get only the needed worksheet
            $data = Excel::selectSheets('preguntas')->load($file)->byConfig('excel::import.sheets',function($reader) use(&$track){
				//row 2 to 420, (answers are on these rows)
				$questionNum = 1;
				//if cell F210 has "10" on it, the file uses the old format, if not, it uses the new format
				$f210 = $reader->sheet->getCell("F210")->getValue();
				//get the lines that need to be retrieved according to the format
				for ($row=2;$row<=420;$row++){
					if (
							( //blank rows and, blank rows on the new format 
								(($row != 164)&&($row != 206)&&($row != 413)&&($row != 414)&&($row != 415))
								&&(($f210!="10")&&!(($row>=407)&&($row<=412)))
							)
							||
							( //blank rows and, discarded rows on the old format
								(($row != 164)&&($row != 206)&&($row != 413)&&($row != 414)&&($row != 415))
								&&(($f210=="10")&&!(($row>=207)&&($row<=209)))
								&&(($f210=="10")&&!(($row>=310)&&($row<=312)))
							)
							
						) {
						//get the data for each question
						$questionYes = $reader->sheet->getCellByColumnAndRow(10,$row)->getValue();//K
						$questionNo = $reader->sheet->getCellByColumnAndRow(11,$row)->getValue();//L
						$questionLink = $reader->sheet->getCellByColumnAndRow(12,$row)->getValue();//M
						$questionComm = $reader->sheet->getCellByColumnAndRow(13,$row)->getValue();//N
						//define which answer it has depending on the values of K and L columns 
						$questionAns = "NA";
						if ((strlen($questionYes)>0) && (strlen($questionNo)==0)) {
							$questionAns = "Y";
						}
						elseif ((strlen($questionYes)==0) && (strlen($questionNo)>0)) {
							$questionAns = "N";
						}
						elseif ((strlen($questionYes)==0) && (strlen($questionNo)==0)) {
							$questionAns = "NA";
						}
						elseif ((strlen($questionYes)>0) && (strlen($questionNo)>0)) {
							$questionAns = "ERROR";
						}
						//create the row in questions table
						$indicator = Indicator::find($questionNum);
						if (!$indicator->tracks->contains($track)){
							$indicator->tracks()->save($track);
						}
						//save the answer in the question model
						$question = Question::where('track_id', '=', $track->id)
										->where('indicator_id', '=', $questionNum)
										->first();
						$question->links = $questionLink;
						$question->q_type = $indicator->q_type;
						$question->answer = $questionAns;
						$question->specialist_comments = $questionComm;
						$question->save();
						echo "<br/>Saved questionId ".$questionNum;
						$questionNum++;
					}
				}
            });
			
        }
        
    }
	
	public function calcFulfillment(){
		dd("deprecated");
		/*if (!$this->active)
			dd("Deactivated option");
		set_time_limit(0);
		$roundId = 1;
		$subjects = Subject::where('enabled', true)
							->get();
		foreach ($subjects as  $subject){
			$tracks = Track::where('round_id','=',$roundId)
							->where('subject_id','=',$subject->id)
							->get();
			foreach ($tracks as $track){
				$yQuestions = Question::where('track_id', $track->id)
										->where('answer', 'Y')
										->where('q_type', 'c')
										->whereNotBetween('indicator_id', [404,408])
										->get();
				$nQuestions = Question::where('track_id', $track->id)
										->where('answer', 'N')
										->where('q_type', 'c')
										->whereNotBetween('indicator_id', [404,408])
										->get();
				$fulfillment = (sizeof($yQuestions) / (sizeof($yQuestions)+sizeof($nQuestions)))*100;
				$track->score = $fulfillment;
				$track->save();
				echo ("</br>".$subject->name." = ".$fulfillment);
			}
		}*/
	}

	public function calcArtFulfillment(){
		if (!$this->active)
			dd("Deactivated option");
		set_time_limit(0);
		$roundId = 2;
		$numerals = Numeral::all();
		foreach ($numerals as $numeral) {
			$countY = 0;
			$countN = 0;
			$indicators = Indicator::where('numeral_id', $numeral->id)
									->get();
			foreach($indicators as $indicator){
				$tracks = Track::where('round_id',$roundId)
								->get();
				foreach($tracks as $track) {
					$yQuestions = Question::where('indicator_id', $indicator->id)
										->where('track_id', $track->id)
										->where('answer', 'Y')
										->where('q_type', 'c')
										->get();
					$countY += sizeof($yQuestions);
					$nQuestions = Question::where('indicator_id', $indicator->id)
										->where('track_id', $track->id)
										->where('answer', 'N')
										->where('q_type', 'c')
										->get();
					$countN += sizeof($nQuestions);
				}
			}
			$numeralTrack = NumeralTrack::where('round_id', $roundId)
											->where('numeral_id', $numeral->id)
											->first();
			if (!$numeralTrack) {
				$numeralTrack = new NumeralTrack;
				$numeralTrack->round_id = $roundId;
				$numeralTrack->numeral_id = $numeral->id;
			}
			if ($countY+$countN>0) 
				$numeralTrack->score = ($countY / ($countY+$countN))*100;
			$numeralTrack->save();
			echo ("</br>".$numeralTrack->numeral_id." = ".$numeralTrack->score);
		}
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
