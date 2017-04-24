<?php

namespace App\Listeners;

use App\Notifications\OnModelCreated;
use App\Notifications\OnModelUpdate;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Star\SimpleMessage\SmsClient;
use Log;

class ModelEventSubscriber
{
    public function onCreated($model)
    {
        if ($model->modelName == 'User') {
            return Log::info(auth()->user()->name .'在' .request()->ip() .'新建了用户:' .$model->getModel());
        }
        $this->handle($model);
    }

    public function onUpdated($model)
    {
        $this->handle($model);
    }

    public function onDeleted($model)
    {
        if ($model->modelName == 'User') {
            return Log::warning(auth()->user()->name .'在' .request()->ip() .'删除了用户:' .$model->getModel());
        }
        $this->handle($model);
    }

    /**
     * 当Model发生变化时，根据action来自动触发事件
     */
    protected function handle($model)
    {       
            $notification = $model->getNotification();
            $model->getModel()->notify(new $notification());
    }

    public function subscribe($event)
    {
        $event->listen(
            'App\Events\ModelCreated',
            'App\Listeners\ModelEventSubscriber@onCreated'
        );
        $event->listen(
            'App\Events\ModelUpdated',
            'App\Listeners\ModelEventSubscriber@onUpdated'
        );
        $event->listen(
            'App\Events\ModelDeleted',
            'App\Listeners\ModelEventSubscriber@onDeleted'
        );
    }
}