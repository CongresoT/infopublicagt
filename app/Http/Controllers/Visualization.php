<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Track;
use App\Round;
use App\Sector;
use App\Article;
use App\NumeralTrack;
use App\Numeral;
use App\Subject;
use App\RoundTrackNumeral;
use App\Indicator;


class Visualization extends Controller
{
	private $higher = 85;
	private $medium = 60;

    public function fulfillment($round_id = null)
    {
		//get requested round, if not requested then get last one
		if ($round_id == null){
			$round = Round::where('is_done', True)
							->orderby('created_at', 'desc')->first();
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not Found");
		}
		$tracks = Track::where('round_id', $round->id)
						->orderby('score','desc')
						->get();
		$highCount = 0;
		$highSum = 0;
		$mediumCount = 0;
		$mediumSum = 0;
		$lowCount = 0;
		$lowSum = 0;
		$trHigh = [];
		$trMedium = [];
		$trLow = [];
		foreach($tracks as $track){
			if($track->score >= $this->higher){
				array_push($trHigh, $track);
				$highSum += $track->score;
				$highCount++;
			}
			elseif($track->score >= $this->medium){
				array_push($trMedium, $track);
				$mediumSum += $track->score;
				$mediumCount++;
			}
			else {
				array_push($trLow, $track);
				$lowSum += $track->score;
				$lowCount++;
			}
		}
		$proms[0] = ($highCount>0?$highSum/$highCount:0);
		$proms[1] = ($mediumCount>0?$mediumSum/$mediumCount:0);
		$proms[2] = ($lowCount>0?$lowSum/$lowCount:0);
		return view('fulfillment_subject', ['round' => $round, 'proms' => $proms, 'highTr' => $trHigh, 'mediumTr' => $trMedium, 'lowTr' => $trLow]);
	}

    public function sector($round_id = null)
    {
		//get all sectors into an asociative array, and related info
		$sectorsDb = Sector::all();
		foreach($sectorsDb as $sector){
			$sectors[$sector->name] = [];
			$sectorsCount[$sector->name] = 0;
			$sectorsSum[$sector->name] = 0;
			$sectorsIds[$sector->name] = $sector->id;
		}
		//get requested round, if not requested then get last one
		if ($round_id == null){
			$round = Round::where('is_done', True)
							->orderby('created_at', 'desc')->first();
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not Found");

		}
		//get all tracks for the round
		$tracks = Track::where('round_id', $round->id)
						->orderby('score','desc')
						->get();
		//get all subjects from the track, and clasify them in the corresponding sector
		foreach($tracks as $track){
			array_push($sectors[$track->subject->sector->name], $track);
			$sectorsCount[$track->subject->sector->name] = $sectorsCount[$track->subject->sector->name]+1;
			$sectorsSum[$track->subject->sector->name] = $sectorsSum[$track->subject->sector->name]+$track->score;
			//set the progress color for the subject
			if ($track->score >= $this->higher){
				$subjectColor[$track->subject->id] = 'progress-green';
			}
			elseif($track->score >= $this->medium){
				$subjectColor[$track->subject->id] = 'progress-yellow';
			}
			else{
				$subjectColor[$track->subject->id] = 'progress-red';
			}
		}
		//calculate avg by sector and sector color bar
		foreach($sectors as $key => $sector){
			$sectorProm[$key] = ($sectorsCount[$key]>0?$sectorsSum[$key]/$sectorsCount[$key]:0);
			if($sectorProm[$key] >= $this->higher){
				$sectorColor[$key] = 'progress-green';
			}
			elseif($sectorProm[$key] >= $this->medium) {
				$sectorColor[$key] = 'progress-yellow';
			}
			else {
				$sectorColor[$key] = 'progress-red';
			}
			
		}
		//sort avg array, descending
		arsort($sectorProm);

		
		return view('sector', ['round' => $round, 'sectors' => $sectors, 'sectorProm' => $sectorProm, 'sectorsIds' => $sectorsIds, 
								'subjectColor' => $subjectColor, 'sectorColor' => $sectorColor]);
	}

    public function numFulfillment($round_id = null)
    {
		//get requested round, if not requested then get last one
		if ($round_id == null){
			$round = Round::where('is_done', True)
							->orderby('created_at', 'desc')->first();
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not Found");
		}
		
		//get all articles to send them and put them on the list
		$articles = Article::all();
		foreach($articles as $article){
			//init variables that will be used to calculate article fulfillment (avg of the numerals)
			$artSum[$article->id] = 0;
			$artCount[$article->id] = 0;
			$artProm[$article->id] = 0;
			$artColor[$article->id] = "";
		}
		//get the score for each numeral
		$numeralTracks = NumeralTrack::where('round_id', $round->id)
										->get();
		foreach($numeralTracks as $track){
			//save the score in an array
			$numScore[$track->numeral_id] = $track->score;
			//define the color to use for the numeral, 
			if ($track->score >= $this->higher){
				$numColor[$track->numeral_id] = 'progress-green';
			}
			elseif($track->score >= $this->medium){
				$numColor[$track->numeral_id] = 'progress-yellow';
			}
			else {
				$numColor[$track->numeral_id] = 'progress-red';
			}
		}
		
		//sum the scores and add one to the count in order to calculate article avg
		foreach($articles as $article){
			foreach($article->numerals as $numeral){
				$artSum[$article->id] += $numScore[$numeral->id];
				$artCount[$article->id] += 1;
			}
		}
		//calculate avg
		foreach($articles as $article){
			if ($artCount > 0){
				$artProm[$article->id] = $artSum[$article->id]/$artCount[$article->id];
			}
			else {
				$artProm[$article->id] = 0;
			}
			//calculate color
			if ($artProm[$article->id] >= $this->higher){
				$artColor[$article->id] = "progress-green";
			}
			elseif ($artProm[$article->id] >= $this->medium){
				$artColor[$article->id] = "progress-yellow";
			}
			else{
				$artColor[$article->id] = "progress-red";
			}

		}
		return view('fulfillment_numeral', ['round' => $round, 'articles' => $articles, 'numScore' => $numScore, 'numColor' => $numColor, 'artProm' => $artProm, 'artColor' => $artColor]);
	}
	
	public function numSorted($round_id = null){
		//get requested round, if not requested then get last one
		if ($round_id == null){
			$round = Round::where('is_done', True)
							->orderby('created_at', 'desc')->first();
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not Found");
		}
		$nTracks = NumeralTrack::where('round_id', $round->id)
								->orderby('score', 'desc')
								->get();
		$art = [];
		$articles = Article::all();
		foreach($articles as $article){
			$art[$article->name] = array();
			$artQty[$article->name] = 0;
			$artSum[$article->name] = 0;
		}
		$numColor;
		foreach($nTracks as $nt){
			$artQty[$nt->numeral->article->name] += 1;
			$artSum[$nt->numeral->article->name] += $nt->score;
			array_push($art[$nt->numeral->article->name], $nt);
			if ($nt->score >= $this->higher){
				$numColor[$nt->numeral_id] = 'progress-green';
			}
			elseif($nt->score >= $this->medium) {
				$numColor[$nt->numeral_id] = 'progress-yellow';
			}
			else {
				$numColor[$nt->numeral_id] = 'progress-red';
			}
		}
		foreach($art as $key => $a){
			$artProm[$key] = 0;
			if ($artQty[$key]>0)
				$artProm[$key] = ($artSum[$key]/$artQty[$key]);
			if ($artProm[$key] >= $this->higher) {
				$artColor[$key] = 'progress-green';
			}
			elseif($artProm[$key] >= $this->medium) {
				$artColor[$key] = 'progress-yellow';
			}
			else {
				$artColor[$key] = 'progress-red';
			}
		}
		return view('numeral_list', ['round'=>$round, 'articles'=>$articles, 'art'=>$art, 'artProm'=>$artProm, 'artColor'=>$artColor, 'numColor'=>$numColor]);
		
	}
	
	public function subject($subject_id, $round_id=null){
		$subject = Subject::find($subject_id);
		if(!$subject)
			dd("404 Not Found");
		if ($round_id == null){
			$rounds = Round::where('is_done', True)
							->orderby('created_at', 'desc')
							->get();
		}
		else{
			$rounds = Round::where([
										['is_done', '=', True],
										['id', '<=', $round_id]
									])
							->orWhere([
										['id', '=', $round_id]
									  ])
							->orderby('created_at','desc')
							->get();
		}
		if ($rounds->isEmpty())
				dd("404 not found");
		$round = $rounds->get(0);
		$round_previous = $rounds->get(1);
		

		$tracks = Track::where('round_id', $round->id)
				->orderby('score','desc')
				->get();
		$ranking = 0;
		$score = 0;
		$advancement = Null;
		//get the ranking
		foreach($tracks as $track){
			$ranking += 1;
			if($track->subject_id == $subject->id){
				$score = $track->score;
				break;
			}
		}
		//get the track info
		$track = Track::where('round_id', $round->id)
						->where('subject_id', $subject->id)
						->first();
		if (!$track)
			dd("404 not Found");
		//get the score of the previous track if exists
		if ($round_previous != null){
			$track_previous = Track::where('round_id', $round_previous->id)
								->where('subject_id', $subject->id)
								->first();
			if ($track_previous != null){
				$advancement = $score - $track_previous->score;
			}
		}
		//get the tracks for the selected rounds, and pack it in one variable to send to the view for the "histogram" visualization
		$roundIds = [];
		foreach($rounds as $round){
			array_push($roundIds, $round->id);
		}
		$tracksSubject = Track::where('subject_id',$subject->id)
								->whereIn('round_id',$roundIds)
								->with('round')
								->get();
		//get info of the cumpl in each numeral
		$rtns = RoundTrackNumeral::where('track_id',$track->id)
									->orderby('score','desc')
									->get();
		
		$qtyTop = 0;
		$qtyMid = 0;
		$qtyLow = 0;
		$sumTop = 0;
		$sumMid = 0;
		$sumLow = 0;
		$topSo = [];
		$midSo = [];
		$lowSo = [];
		$promTop = 0;
		$promMid = 0;
		$promLow = 0;
		foreach($rtns as $rtn){
			//exclude numeral_id=56 "buenas practicas"
			if ($rtn->numeral_id == 56)
				continue;
			if ($rtn->score >= $this->higher){
				array_push($topSo, $rtn);
				$qtyTop++;
				$sumTop += $rtn->score;
			}
			elseif($rtn->score >= $this->medium){
				array_push($midSo, $rtn);
				$qtyMid++;
				$sumMid += $rtn->score;
			}
			else{
				array_push($lowSo, $rtn);
				$qtyLow++;
				$sumLow += $rtn->score;
			}
		}
		if ($qtyTop>0)
			$promTop = $sumTop/$qtyTop;
		if ($qtyMid>0)
			$promMid = $sumMid/$qtyMid;
		if ($qtyLow>0)
			$promLow = $sumLow/$qtyLow;
		
		return view('subject', ['subject'=>$subject, 'ranking'=>$ranking, 'score'=>$score, 'topSo'=>$topSo, 'midSo' => $midSo, 
					'lowSo' => $lowSo, 'promTop' => $promTop, 'promMid' => $promMid, 'promLow' => $promLow, 'advancement' => $advancement,
					'tracksSubject' => $tracksSubject, 'higher'=>$this->higher, 'medium'=>$this->medium]);
	}
	
	public function subjectPDF($subject_id, $round_id=null){
		set_time_limit(0);
		$subject = Subject::find($subject_id);
		if(!$subject)
			dd("404 Not Found");
		if ($round_id == null){
			$round = Round::where('is_done', True)
							->orderby('created_at', 'desc')->first();
		}
		else{
			$round = Round::find($round_id);
			if (!$round)
				dd("404 Not Found");
		}
		$tracks = Track::where('round_id', $round->id)
				->orderby('score','desc')
				->get();
		$ranking = 0;
		$score = 0;
		foreach($tracks as $track){
			$ranking += 1;
			if($track->subject_id == $subject->id){
				$score = $track->score;
				break;
			}
		}
		$track = Track::where('round_id', $round->id)
						->where('subject_id', $subject->id)
						->first();
		if (!$track)
			dd("404 not Found");
		$rtns = RoundTrackNumeral::where('track_id',$track->id)
									->orderby('score','desc')
									->get();
		
		$indicators = Indicator::all();
		
		//return view('pdf.subject', ['subject'=>$subject, 'ranking'=>$ranking, 'score'=>$score, 'track'=>$rtns, 'higher'=>$this->higher, 'medium'=>$this->medium]);
		
		$pdf = \PDF::loadView('pdf.subject', ['subject'=>$subject, 'ranking'=>$ranking, 'score'=>$score, 'track'=>$rtns, 'higher'=>$this->higher, 'medium'=>$this->medium]);
		return $pdf->stream($subject->name.' - '.$round->name.'.pdf');

	}

	public function numeral($numeral_id, $round_id=null){
		$numeral = Numeral::find($numeral_id);
		if(!$numeral)
			dd("404 Not Found");
		if ($round_id == null){
			$rounds = Round::where('is_done', True)
							->orderby('created_at', 'desc')
							->get();
		}
		else{
			$rounds = Round::where([
										['is_done', '=', True],
										['id', '<=', $round_id]
									])
							->orWhere([
										['id', '=', $round_id]
									  ])
							->orderby('created_at','desc')
							->get();
		}
		if ($rounds->isEmpty())
				dd("404 not found");
		$round = $rounds->get(0);
		$round_previous = $rounds->get(1);

		$tracks = NumeralTrack::where('round_id', $round->id)
				->orderby('score','desc')
				->get();
		$ranking = 0;
		$score = 0;
		//get the ranking of the numeral
		foreach($tracks as $track){
			$ranking += 1;
			if($track->numeral_id == $numeral->id){
				$score = $track->score;
				break;
			}
		}
		
		
		//get the score of the previous round
		$advancement = Null;
		if ($round_previous != null){
			$track_previous = NumeralTrack::where('round_id', $round_previous->id)
								->where('numeral_id', $numeral->id)
								->first();
			if ($track_previous != null){
				$advancement = $score - $track_previous->score;
			}
		}
		
		
		//get the tracks for the selected rounds, and pack it in one variable to send to the view for the "histogram" visualization
		$roundIds = [];
		foreach($rounds as $round){
			array_push($roundIds, $round->id);
		}
		$tracksNumeral = NumeralTrack::where('numeral_id',$numeral->id)
								->whereIn('round_id',$roundIds)
								->with('round')
								->get();
		//get the info for each subject 
		
		
		$tracks = Track::where('round_id',$round->id)
						->get();
		$trackIds = [];
		foreach($tracks as $track) {
			array_push($trackIds, $track->id);
		}
		$rtns = RoundTrackNumeral::where('numeral_id',$numeral->id)
									->whereIn('track_id',$trackIds)
									->orderby('score','desc')
									->get();

		$qtyTop = 0;
		$qtyMid = 0;
		$qtyLow = 0;
		$sumTop = 0;
		$sumMid = 0;
		$sumLow = 0;
		$topSo = [];
		$midSo = [];
		$lowSo = [];	
		$promTop = 0;
		$promMid = 0;
		$promLow = 0;
		foreach($rtns as $rtn){
			if ($rtn->score >= $this->higher){
				array_push($topSo, $rtn);
				$qtyTop++;
				$sumTop += $rtn->score;
			}
			elseif($rtn->score >= $this->medium){
				array_push($midSo, $rtn);
				$qtyMid++;
				$sumMid += $rtn->score;
			}
			else{
				array_push($lowSo, $rtn);
				$qtyLow++;
				$sumLow += $rtn->score;
			}			
		}
		if ($qtyTop>0)
			$promTop = $sumTop/$qtyTop;
		if ($qtyMid>0)
			$promMid = $sumMid/$qtyMid;
		if ($qtyLow>0)
			$promLow = $sumLow/$qtyLow;

		return view('numeral', ['numeral'=>$numeral, 'ranking'=>$ranking, 'score'=>$score, 'topSo'=>$topSo, 'midSo' => $midSo, 
					'lowSo' => $lowSo, 'promTop' => $promTop, 'promMid' => $promMid, 'promLow' => $promLow, 'advancement' => $advancement,
					'tracksNumeral' => $tracksNumeral, 'higher'=>$this->higher, 'medium'=>$this->medium]);
	}
	
	public function advancement($round_id = null){
		if ($round_id == null){
			$rounds = Round::where('is_done', True)
							->orderby('created_at', 'desc')
							->get();
		}
		else{
			$rounds = Round::where([
										['is_done', '=', True],
										['id', '<=', $round_id]
									])
							->orWhere([
										['id', '=', $round_id]
									  ])
							->orderby('created_at','desc')
							->get();
		}
		if ($rounds->isEmpty())
			dd("404 not found");
		$round = $rounds->get(0);
		$round_previous = $rounds->get(1);
		if ($round_previous == null)
			dd("No hay monitoreo anterior para comparar");
		
		$tracks = DB::table('subjects')
					->join('tracks as r1', function($join) use($round){
										$join->on('subjects.id', '=', 'r1.subject_id');
									})
					->join('tracks as r2', function($join) use($round_previous){
										$join->on('subjects.id', '=', 'r2.subject_id');
									})
					->where('r1.round_id', $round->id)
					->where('r2.round_id', $round_previous->id)
					->orderby('advancement', 'desc')
					->select('subjects.*', 'r1.score as new_score', 'r2.score as old_score', DB::raw('(r1.score - r2.score) as advancement'))
					->get();
		
		$subjectsUp = [];
		$subjectsEqual = [];
		$subjectsDown = [];
		$upCount = 0;
		$equalCount = 0;
		$downCount = 0;

		foreach($tracks as $track){
			if ($track->advancement > 0){
				array_push($subjectsUp, $track);
				$upCount++;
			}
			elseif($track->advancement < 0){
				array_push($subjectsDown, $track);
				$downCount++;
			}
			else{
				array_push($subjectsEqual, $track);
				$equalCount++;
			}
		}
		$upPerc = ($upCount * 100)/($upCount+$downCount+$equalCount);
		$equalPerc = ($equalCount * 100)/($upCount+$downCount+$equalCount);
		$downPerc = ($downCount * 100)/($upCount+$downCount+$equalCount);
		
		return view('advancement', ['round'=>$round, 'round_previous'=>$round_previous, 'subjectsUp'=>$subjectsUp, 'subjectsEqual'=>$subjectsEqual, 'subjectsDown'=>$subjectsDown, 'upPerc'=>$upPerc, 'equalPerc'=>$equalPerc, 'downPerc'=>$downPerc]);
	}
}
