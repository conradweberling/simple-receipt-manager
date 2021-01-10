<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class Registered
{
    use SerializesModels;

    public $user;
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param $invitation
     */
    public function __construct($user, $invitation)
    {
        $this->user = $user;
        $this->invitation = $invitation;
    }
}
