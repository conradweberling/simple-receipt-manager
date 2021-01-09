<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationSaved
{
    use Dispatchable, SerializesModels;

    public $email;
    public $token;

    /**
     * Create a new event instance.
     *
     * @param $email
     * @param $token
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

}
