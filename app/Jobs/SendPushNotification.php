<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $fcmToken;
    protected string $title;
    protected string $body;

    public function __construct(string $fcmToken, string $title, string $body)
    {
        $this->fcmToken = $fcmToken;
        $this->title = $title;
        $this->body = $body;
    }

    public function handle(): void
    {
        $messaging = app('firebase.messaging');

        $message = CloudMessage::new()
            ->toToken($this->fcmToken)
            ->withNotification(Notification::create($this->title, $this->body));

        $messaging->send($message);
    }
}
