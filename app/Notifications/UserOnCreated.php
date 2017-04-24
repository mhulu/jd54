<?php

namespace App\Notifications;

use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Request;

class UserOnCreated extends BaseNotification
{

    /**
     * 注册成功，把IP信息入一次库，看做是已经登录一次了
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $ip = Request::ip();
        $notifiable->last_ip = $ip;
        $notifiable->last_login = Carbon::now();
        $notifiable->save();
        return [
            'title' => '您的账号于'.Carbon::now().'在'.$ip.'成功注册',
            'content' => '「半亩方塘一鉴开，天光云影共徘徊」，希望本系统能为您带来愉快的工作心情。',
            'type' => 'success'
        ];
    }
}
