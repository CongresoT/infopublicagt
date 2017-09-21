<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Mail\Mailer;
use App\RoundTrackNumeral;
use App\Round;
use App\Subject;	


class SendReportEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $ranking;
	protected $subject;
	protected $score;
	protected $rtns;
	protected $higher;
	protected $medium;
	protected $round;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subject $subject, $ranking, $score, $rtns, $higher, $medium, $round)
    {
		$this->subject = $subject;
		$this->ranking = $ranking;
		$this->score = $score;
		$this->rtns = $rtns;
		$this->higher = $higher;
		$this->medium = $medium;
		$this->round = $round;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
		$subject = $this->subject;
		$score = $this->score;
		$rtns = $this->rtns;
		$higher = $this->higher;
		$medium = $this->medium;
		$round = $this->round;
		$ranking = $this->ranking;
		$mailData['subject_id'] = $subject->id;
		$mailData['round_name'] = $round->name;
		$pdf = \PDF::loadView('pdf.subject', ['subject'=>$subject, 'ranking'=>$ranking, 'score'=>$score, 'track'=>$rtns, 'higher'=>$higher, 'medium'=>$medium]);
		$mailer->send('emails.email', $mailData, function($msg) use($pdf, $subject, $round) { 
			$msg->to(array_map('trim',explode(",",$subject->email)), $subject->uaip_person)
					->subject('Resultados para '.$subject->name.' - '.$round->name.'.pdf')
					->from(env('MAIL_USERNAME'),env('MAIL_SENDERNAME'))
					->attachData($pdf->output(), $subject->name.' - '.$round->name.'.pdf');
		});
    }
}
