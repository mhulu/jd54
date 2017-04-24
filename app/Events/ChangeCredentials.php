<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ChangeCredentials
{
    use Dispatchable, SerializesModels;

    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

}
