<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'unit' => $user->unit->name,
            'status' =>$user->status,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'last_login' => $user->last_login
        ];
    }
}
