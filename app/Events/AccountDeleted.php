<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class AccountDeleted
{
    use SerializesModels;

    public $name;
    public $email;

    /**
     * Create a new event instance.
     *
     * @param $name
     * @param $email
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

}
