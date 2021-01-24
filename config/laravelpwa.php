<?php

return [
    'name' => 'SimpleReceiptManager',
    'manifest' => [
        'name' => env('APP_NAME', 'SimpleReceiptManager'),
        'short_name' => 'SRM',
        'start_url' => env('APP_URL').'/',
        'background_color' => '#ffffff',
        'theme_color' => '#ffffff',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> '#ffffff',
        'icons' => [
            '128x128' => [
                'path' => env('APP_URL').'/images/icons/receipt-128x128.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => env('APP_URL').'/images/icons/receipt-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'custom' => []
    ]
];
