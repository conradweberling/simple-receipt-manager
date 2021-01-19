<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceiptDestroyedByOtherNotification extends Notification
{
    use Queueable;

    protected const LANG_PRE = 'notifications.'.self::class.'.';

    protected $user;
    protected $date;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $date
     */
    public function __construct($user, $date)
    {
        $this->user = $user;
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject( __(self::LANG_PRE.'subject'))
            ->line(__(self::LANG_PRE.'message', ['date' => $this->date, 'name' => $this->user->name]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => __(self::LANG_PRE.'subject'),
            'message' => __(self::LANG_PRE.'message', ['date' => $this->date, 'name' => $this->user->name])
        ];
    }
}
