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
use Illuminate\Support\Facades\DB;

class Load extends Controller
{

	private $active = True;
    private $roundId = 2;
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
		
		
		//$questionMap will save the map between file row and indicatorId
		$questionMap = [];
		//first questions
		$qNum = 1;
		for ($rowNum = 2;$rowNum <= 76;$rowNum++){
			//row 2 has question 1, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//recently added questions, for num14
		$qNum = 409;
		for ($rowNum = 77;$rowNum <= 81;$rowNum++){
			//row 77 has question 409, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//recently added questions, for num15
		$qNum = 414;
		for ($rowNum = 97;$rowNum <= 111;$rowNum++){
			//row 77 has question 409, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//continue with the older questions
		$qNum = 101;
		for ($rowNum = 112;$rowNum <= 173;$rowNum++){
			//row 112 has question 101, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//article 11 to 14
		$qNum = 163;
		for ($rowNum = 175;$rowNum <= 215;$rowNum++){
			//row 175 has question 163, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//art 10, num 24 and 25
		$qNum = 204;
		for ($rowNum = 217;$rowNum <= 416;$rowNum++){
			//row 217 has question 204, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}
		//good practices
		$qNum = 404;
		for ($rowNum = 426;$rowNum <= 430;$rowNum++){
			//row 426 has question 404, and etc
			$questionMap[$rowNum] = $qNum;
			$qNum++;
		}		
		
		//retrieve all files in $directory
		//this script will only handle the latest format of the questions (the defined for the second row), 
		//if you need to load 1st round files ..convert them or use git to get the previous file
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
            $data = Excel::selectSheets('preguntas')->load($file)->byConfig('excel::import.sheets',function($reader) use(&$track, $questionMap){
				//row 2 to 430, (answers are on these rows)				
				for ($row=2;$row<=430;$row++){
					//if the row has data that needs to be saved
					if (isset($questionMap[$row])) {
						//get the data for each question
						$questionYes = $reader->sheet->getCellByColumnAndRow(10,$row)->getValue();//K
						$questionNo = $reader->sheet->getCellByColumnAndRow(11,$row)->getValue();//L
						$questionLink = $reader->sheet->getCellByColumnAndRow(12,$row)->getValue();//M
						$questionComm = $reader->sheet->getCellByColumnAndRow(13,$row)->getValue();//N
						//define which answer it has depending on the values of K and L columns 
						$questionAns = "NA";
						if ((strtoupper(trim($questionYes)) == "X") && (strtoupper(trim($questionNo)) != "X")) {
							$questionAns = "Y";
						}
						elseif ((strtoupper(trim($questionYes)) != "X") && (strtoupper(trim($questionNo)) == "X")) {
							$questionAns = "N";
						}
						elseif ((strtoupper(trim($questionYes)) != "X") && (strtoupper(trim($questionNo)) != "X")) {
							$questionAns = "NA";
						}
						elseif ((strtoupper(trim($questionYes)) == "X") && (strtoupper(trim($questionNo)) == "X")) {
							$questionAns = "ERROR";
							dd("row# ".$row.": error, invalid value");
						}
						//create the row in questions table
						$indicator = Indicator::find($questionMap[$row]);
						if (!$indicator->tracks->contains($track)){
							$indicator->tracks()->save($track);
						}
						//save the answer in the question model
						$question = Question::where('track_id', '=', $track->id)
										->where('indicator_id', '=', $questionMap[$row])
										->first();
						$question->links = $questionLink;
						$question->q_type = $indicator->q_type;
						$question->answer = $questionAns;
						$question->specialist_comments = $questionComm;
						$question->save();
						echo "<br/>Saved questionId ".$questionMap[$row]." ".$questionAns." (on row ".$row.")";
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
		$roundId = $this->roundId;
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
		$roundId = $this->roundId;
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
		$roundId = $this->roundId;		
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

	public function createCsv() {
		if (!$this->active)
			dd("Deactivated option");
		$roundId = $this->roundId;
        $roundInfo = DB::table('questions as q')
                        ->join('indicators as i', function($join) {
                            $join->on('q.indicator_id','=','i.id');
                        })
                        ->join('numerals as n', function($join) {
                            $join->on('i.numeral_id','=','n.id');
                        })
                        ->join('articles as a', function($join) {
                            $join->on('n.article_id','=','a.id');
                        })
                        ->join('tracks as t', function($join) {
                            $join->on('q.track_id','=','t.id');
                        })
                        ->join('rounds as r', function($join) {
                            $join->on('t.round_id','=','r.id');
                        })
                        ->join('subjects as s', function($join) {
                            $join->on('t.subject_id','=','s.id');
                        })
                        ->join('sectors as sec', function($join) {
                            $join->on('s.sector_id','=','sec.id');
                        })
                        ->where('r.id',2)
                        ->where([
                            ['q.answer','!=','NA'],
                            ['q.answer','!=',Null]
                        ])
                        ->orderby('s.name','asc')
                        ->select('s.name as subject', 'sec.name as sector', 'r.name as round', 'a.name as article', 'n.name as numeral', 'i.question as question', 'q.q_type as q_type', 'q.answer as answer')
                        ->get();
        $roundInfo = collect($roundInfo)->map(function($x){ return(array) $x; })->toArray();
        //dd($roundInfo);
        \Excel::create('infopublicagt_monitoreo_'.$this->roundId, function($excel) use($roundInfo) {
            $excel->sheet('monitoreo'.$this->roundId, function($sheet) use ($roundInfo) {
                $sheet->fromArray($roundInfo);
            });
        })->store('csv', storage_path('monitoreos'));
        dd('file saved');
	}
    
	public function createSoCsv() {
		if (!$this->active)
			dd("Deactivated option");
        $subjectsInfo = Subject::select('id','name as nombre', 'url', 'uaip_person', 'phone as telefono', 'email')
                            ->get()->toArray();
        \Excel::create('so', function($excel) use($subjectsInfo) {
            $excel->sheet('so', function($sheet) use ($subjectsInfo) {
                $sheet->fromArray($subjectsInfo);
            });
        })->store('csv', storage_path('monitoreos'));
        dd('file saved');
	}
}
