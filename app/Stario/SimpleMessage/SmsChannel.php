<?php
/**
 * ***************************************************
 *  * Copyright (C) STARIO.NET - All Rights Reserved
 *  * @file        SmsChannel.php
 *  * @author      Partoo
 *  * @site       http://www.stario.net
 *  * @date        2017-02-21 15:16:56
 *
 */

namespace Star\SimpleMessage;
use Illuminate\Notifications\Notification;

class SmsChannel
{
	protected $smsClient;
	protected $message;

	public function __construct(SmsClient $smsClient)
	{
		$this->smsClient = $smsClient;
	}

	public function send($notifiable, Notification $notification)
	{
		$to = $notifiable->routeNotificationFor('sms');
		if (empty($to)) {
			throw CouldNotSendNotification::missingRecipient();
		}
		$message = $notification->toSms($notifiable);

        return $this->smsClient->type($message->type)->to($message->recipient)->send();
    }

}