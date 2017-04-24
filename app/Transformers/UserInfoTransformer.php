<?php

namespace App\Transformers;

use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserInfoTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $profile = $user->profile;
        $permissions = $user->getAllPermissions()->map( function ($item) {
            return $item->name;
        });
        $roles = $user->roles->map( function ($item) {
            return $item->name;
        });
        if (empty($user->profile)) {
            $profile = null;
        }
        // $unreadNotifications = $user->unreadNotifications->map( function ($item) {
        //     return [
        //         'type' => $item->notifiable_type,
        //         'data' => $item->data
        //     ];
        // });
         return [
            'id' => $user->id,
            'name' => $user->name,
            'status' => $user->status,
            'mobile' => $user->mobile,
            'unit' => $user->unit->id,
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
            'updated_at' => $user->updated_at->toDateTimeString(),
            // 'birthplace' => $profile == null ? '未完善' : $profile->birthplace,
            // 'unreadNotifications' => $unreadNotifications->toArray(),
        ];
    }
}
