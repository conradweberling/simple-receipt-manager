<?php

return [
    'name' => 'SimpleReceiptManager',
    'manifest' => [
        'name' => env('APP_NAME', 'SimpleReceiptManager'),
        'short_name' => 'SRM',
        'start_url' => url('/').'/',
        'background_color' => '#ffffff',
        'theme_color' => '#ffffff',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> '#ffffff',
        'icons' => [
            '128x128' => [
                'path' => asset('/images/icons/receipt-128x128.png'),
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => asset('/images/icons/receipt-512x512.png'),
                'purpose' => 'any'
            ],
        ],
        'custom' => []
    ]
];
