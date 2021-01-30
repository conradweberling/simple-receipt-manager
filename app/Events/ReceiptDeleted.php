<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReceiptDeleted
{
    use Dispatchable, SerializesModels;

    public $user;
    public $date;
    public $amount;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $date
     * @param $amount
     */
    public function __construct($user, $date, $amount)
    {
        $this->user = $user;
        $this->date = $date;
        $this->amount = $amount;
    }

}
