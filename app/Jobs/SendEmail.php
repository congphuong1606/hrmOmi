<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    var $toMail;
    var $ccMails;
    var $mailData;
    var $mailSubject;
    var $mailView;

    /**
     * Create a new job instance.
     *
     * @param string $toMail
     * @param array $ccMails
     * @param array $mailData
     * @param string $mailView
     * @param string $mailSubject
     */
    public function __construct($toMail = '', $ccMails = array(), $mailData = array(), $mailView = '', $mailSubject = '')
    {
        //
        $this->toMail = $toMail;
        $this->ccMails = array_unique($ccMails);
        $this->mailData = $mailData;
        $this->mailSubject = $mailSubject;
        $this->mailView = $mailView;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public
    function handle()
    {
        try {
            $this->sendMail($this->toMail, $this->ccMails, $this->mailSubject);
        } catch (\Exception $e) {
            \Log::info($e->getTraceAsString());
        }

    }

    private
    function sendMail($toMail, $ccMails, $mailSubject)
    {
        \Log::info('sendMail: ' . $toMail);
        \Log::info('sendMail: ' . json_encode($ccMails));
        if ($toMail == null) {
            return;
        }
        try {
            if (config('app.env') === 'production') {
                Mail::send($this->mailView, $this->mailData, function ($message) use ($toMail, $ccMails, $mailSubject) {
                    $message->from('noreply@talent.ominext.com', 'Ominext Adminstration System');
                    if (empty($ccMails)) {
                        $message->subject($mailSubject)->to($toMail);
                    } else {
                        $message->subject($mailSubject)->to($toMail)->cc($ccMails);
                    }
                });
            } else {
                Mail::send($this->mailView, $this->mailData, function ($message) use ($toMail, $ccMails, $mailSubject) {
                    $message->from('noreply@talent.ominext.com', 'Ominext Adminstration System');
                    $message->to('vietnq@ominext.com')->subject($mailSubject . '(TO)Redirect from: ' . $toMail);
                });

                Mail::send($this->mailView, $this->mailData, function ($message) use ($toMail, $ccMails, $mailSubject) {
                    $message->from('noreply@talent.ominext.com', 'Ominext Adminstration System');
                    $message->to('ominextmailtest@gmail.com')->subject($mailSubject . '(TO)Redirect from: ' . $toMail);
                });
                foreach ($ccMails as $ccMail) {
                    Mail::send($this->mailView, $this->mailData, function ($message) use ($ccMail, $mailSubject) {
                        $message->from('noreply@talent.ominext.com', 'Ominext Adminstration System');
                        $message->to('vietnq@ominext.com')->subject($mailSubject . '(CC)Redirect from: ' . $ccMail);
                    });
                    Mail::send($this->mailView, $this->mailData, function ($message) use ($ccMail, $mailSubject) {
                        $message->from('noreply@talent.ominext.com', 'Ominext Adminstration System');
                        $message->to('ominextmailtest@gmail.com')->subject($mailSubject . '(CC)Redirect from: ' . $ccMail);
                    });
                }
            }

            \Log::info('Send mail done');
        } catch (\Exception $e) {
            \Log::info($e->getTraceAsString());
        }
    }
}
