<?php

namespace App\Listeners;

use App\Notifications\UserOnChangedCredential;
use App\Notifications\UserOnCreated;
use App\Notifications\UserOnLockout;
use App\Notifications\UserOnLogin;
use App\Notifications\UserOnRegistered;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Star\SimpleMessage\SmsClient;

class UserEventSubscriber 
{
    /**
     * 当登录时IP地址与上次不同时，触发notification
     */
    public function onLogin($event)
    {
        if ($event->user->last_ip != request()->ip() && !empty($event->user->last_ip)) {
            $event->user->notify(new UserOnLogin);
        }
        $event->user->last_ip = request()->ip();
        $event->user->last_login = Carbon::now();
        $event->user->save();
    }

    public function onLockout($event)
    {
        $user = User::where('mobile', $event->request->mobile)->get()->first();
        if ($user) {
            $user->notify(new UserOnLockout);
            return (new SmsClient)->to($user->mobile)->type('error')->send();
        }
    }

    public function onCreated($event)
    {
        if ($event->user->id == 1) {
            $event->user->assignRole('admin');
        }
        $event->user->assignRole('user');
        $event->user->notify(new UserOnCreated);
        // return (new SmsClient)->to($event->user->mobile)->type('success')->send();
    }

    public function onChangeCredentials($event)
    {
        $event->user->notify(new UserOnChangedCredential);
    }

    public function subscribe($event)
    {
        $event->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onLogin'
        );
        $event->listen(
            'Illuminate\Auth\Events\Lockout',
            'App\Listeners\UserEventSubscriber@onLockout'
        );
        $event->listen(
            'App\Events\UserCreated',
            'App\Listeners\UserEventSubscriber@onCreated'
        );
        $event->listen(
            'App\Events\ChangeCredentials',
            'App\Listeners\UserEventSubscriber@onChangeCredentials'
        );
    }
}
