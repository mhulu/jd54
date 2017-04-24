<?php

namespace App\Transformers;

use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MeInfoTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $profile = $user->profile;
        $permissions = $user->getAllPermissions()->map( function ($item) {
            return [
                'name' => $item->name,
                'label' => $item->label 
            ];
        });
        $roles = $user->roles->map( function ($item) {
            return [
                'name' => $item->name,
                'label' => $item->label 
            ];
        });
        if (empty($user->profile)) {
            $profile = null;
            $diffMyDays = '未知';
        } else {
            Carbon::setLocale('zh');
            $date = Carbon::createFromFormat('Y-m-d', $profile->birthday);
            $future = Carbon::createFromDate(null, $date->month, $date->day);
            $diffMyDays = $future->diffForHumans(Carbon::now());
        }
        $unreadNotifications = $user->unreadNotifications->map( function ($item) {
            return [
                'type' => $item->notifiable_type,
                'data' => $item->data
            ];
        });
         return [
            'id' => $user->id,
            'name' => $user->name,
            'status' => $user->status,
            'mobile' => $user->mobile,
            'unit' => $user->unit->name,
            'email' => $user->email == null ? '' : $user->email,
            'last_ip' => $user->last_ip,
            'last_login' => $user->last_login,
            // 'profileNull' => (bool) $profile == null,
            'profile' => [
                'nickname' => $profile == null ? '未名' : $profile->nickname,
                'qq' => $profile == null ? '' : $profile->qq,
                'avatar' => $profile == null ? 'http://static.stario.net/images/avatar.png' : $profile->avatar,
                'birthday' => $profile == null ? '1976-01-01' : $profile->birthday,
                'sex' => $profile == null ? 0 : $profile->sex,
            ],
            'rolemission' => [
                'permissions' => $permissions->toArray(),
                'roles' => $roles->toArray()
            ],
            'created_at' => $user->created_at->toDateTimeString(),
            'diffMyDays' => $diffMyDays,
            // 'birthplace' => $profile == null ? '未完善' : $profile->birthplace,
            // 'unreadNotifications' => $unreadNotifications->toArray(),
        ];
    }
}
