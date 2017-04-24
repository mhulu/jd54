<?php

namespace App\Notifications;

use App\Notifications\BaseNotification;
use Carbon\Carbon;

class UserOnLogin extends BaseNotification
{

    /**
     * 为了简化数据库，只在ip发生变化时入库，判断逻辑位于UserEventSubscriber中
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
             'title' => '登录IP有变化，本次于：'.Carbon::now().'在'.request()->ip().'登录',
             'content' => '如果您在不同的网络环境，如办公室登录后又在家中登录，就会引起IP地址的变化。您可以通过ip.cn进行进一步的参考。',
             'type' => 'warning'
        ];
    }
}
