<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

}
