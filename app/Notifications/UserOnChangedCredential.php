<?php

namespace App\Notifications;

use App\Notifications\BaseNotification;
use Carbon\Carbon;

class UserOnChangedCredential extends BaseNotification
{

    /**
     * 注册成功，把IP信息入一次库，看做是已经登录一次了
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notifiable->last_ip = request()->ip();
        $notifiable->last_login = Carbon::now();
        $notifiable->save();
        return [
            'title' => '您的账号登录凭证发生改变',
            'content' => '您的账号于登录凭证（手机号码或密码）曾于'.Carbon::now().'在'.request()->ip().'被修改。',
            'type' => 'danger'
        ];
    }
}
