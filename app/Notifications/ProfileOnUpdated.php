<?php

namespace App\Notifications;

use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Request;

class ProfileOnUpdated extends BaseNotification
{

    public function toArray($notifiable)
    {
        $ip = Request::ip();
        return [
            'title' => '您的个人资料成功被修改',
            'content' => '您的账号于'.Carbon::now().'在'.$ip.'成功修改',
            'type' => 'success'
        ];
    }
}
