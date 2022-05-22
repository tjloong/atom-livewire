<?php

return [
    'locales' => ['en'],
    'max_upload_size' => 8,
    'timezone' => 'Asia/Kuala_Lumpur',
    'payment_gateway' => [],

    /**
     * Account Portal
     */
    'accounts' => [
        'verify' => true,
        'register' => true,
        'login' => [
            'facebook',
            'google',
            'linkedin',    
        ],
    ],

    /**
     * App Portal
     */
    'app' => [
        'users' => [
            'data_visibility' => true,
        ],
        
        'permissions' => [
            'users' => ['manage'],
            'labels' => ['manage'],
            'files' => ['manage'],
            'site-settings' => ['manage'],
        ],
    ],
];