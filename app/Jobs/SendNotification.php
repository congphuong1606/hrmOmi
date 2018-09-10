<?php

namespace App\Jobs;

use App\Helpers\NotificationHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    var $receivers;
    var $title;
    var $body;
    var $type;
    var $sourceId;

    /**
     * Create a new job instance.
     *
     * @param $receivers
     * @param $body
     * @param $title
     * @param $type
     * @param $sourceId
     */
    public function __construct($receivers = array(), $body = '', $title = '', $type, $sourceId)
    {
        //
        $this->receivers = $receivers;
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->sourceId = $sourceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {

            $this->saveNotification();
            $devices = NotificationHelper::getFcmTokensFromMails($this->receivers);
            NotificationHelper::sendNotification($devices, $this->body, $this->title);
        } catch (\Exception $e) {
            \Log::info($e->getTraceAsString());
        }

    }

    private function saveNotification()
    {
        $rememberedEmail = null;
        foreach ($this->receivers as $receiver) {
            if ($rememberedEmail != $receiver) {
                $notification = NotificationHelper::getNotificationMessageFromType(DEVICES_TYPE_WEB, $this->body, $this->title);
                $title = $notification != null ? $notification['title'] : null;
                $body = $notification != null ? $notification['body'] : null;
                $action = '';
                NotificationHelper::saveNotification($title, $body, $action, $receiver, $this->type, $this->sourceId);
            }
            $rememberedEmail = $receiver;
        }
    }
}
