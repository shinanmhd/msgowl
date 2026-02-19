<?php

namespace Hadhiya\MsgOwl\Channels;

use Hadhiya\MsgOwl\MsgOwl;
use Hadhiya\MsgOwl\MsgOwlMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MsgOwlChannel
{
    public function __construct(
        protected MsgOwl $msgOwl
    ) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toMsgOwl')) {
            return;
        }
        
        $message = $notification->toMsgOwl($notifiable);

        if (is_string($message)) {
            $message = MsgOwlMessage::create($message);
        }

        $params = $message instanceof MsgOwlMessage ? $message->toArray() : $message;
        $params['recipients'] ??= $notifiable->routeNotificationFor('msgowl', $notification);

        // Check for dryRun
        if ($message instanceof MsgOwlMessage && $message->isDryRun) {
            Log::info('MsgOwl Dry Run:', [
                'to' => $params['recipients'],
                'body' => $params['body'],
                'sender' => $params['sender_id'] ?? config('msgowl.sender_id'),
            ]);
            return;
        }

        if (empty($params['recipients'])) {
            return;
        }

        $this->msgOwl->send($params);
    }
}