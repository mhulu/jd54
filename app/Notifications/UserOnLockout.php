<?php

namespace App\Notifications;

use App\Notifications\BaseNotification;
use Carbon\Carbon;

class UserOnLockout extends BaseNotification
{

    public function toArray($notifiable)
    {
        return [
            'title' => '您的账号被锁定',
            'content' => '您的当前账号曾于'.Carbon::now().'在'.request()->ip().'登录时被锁定，出现这种情况通常是由于连续输入错误的账户密码，也有可能是操作不当造成的。如果IP地址不为您所常用，请您与管理员联系',
            'type' => 'danger'
        ];
    }
}
