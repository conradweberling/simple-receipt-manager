<?php

return [

    \App\Notifications\AcceptedNotification::class => [
        'subject' => 'Invitation accepted',
        'message' => ':name accepted your invitation.',
    ],

    \App\Notifications\AccountDeletedNotification::class => [
        'subject' => 'Account was successfully deleted',
        'message' => 'Your account and all your stored data have been permanently deleted.',
    ],

    \App\Notifications\InvitationNotification::class => [
        'subject' => 'Invitation',
        'message' => 'You have been invited to use :name.',
        'button' => 'Accept invitation'
    ],

    \App\Notifications\ReceiptDestroyedByOtherNotification::class => [
        'subject' => 'One of your receipts has been deleted',
        'message' => 'Your receipt from :date was deleted by :name.'
    ],

    \App\Notifications\WelcomeNotification::class => [
        'subject' => 'Welcome :name',
        'message' => 'Registration was successfully completed. Thank you for using our application!',
    ]

];
