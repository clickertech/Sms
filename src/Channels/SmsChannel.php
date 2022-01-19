<?php

namespace Clickertech\Sms\Channels;

use Exception;
use Clickertech\Sms\Builder;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        /**
         * @psalm-suppress UndefinedMethod
         */
        $message = $notification->toSms($notifiable);

        $this->validate($message);
        $manager = app()->make('clickertech-sms');

        if (!empty($message->getDriver())) {
            $manager->via($message->getDriver());
        }

        return $manager->send($message->getBody(), function ($sms) use ($message) {
            $sms->templateId($message->getTemplateId());
            $sms->to($message->getRecipients());
        });
    }

    private function validate($message)
    {
        $conditions = [
            'Invalid data for sms notification.' => !is_a($message, Builder::class),
            'Message body could not be empty.' => empty($message->getBody()),
            'Message templateid could not be empty.' => empty($message->getTemplateId()),
            'Message recipient could not be empty.' => empty($message->getRecipients()),
        ];

        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new Exception($ex));
        }
    }
}
