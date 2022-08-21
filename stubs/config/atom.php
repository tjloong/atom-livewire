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
        'user' => [
            'data_visibility' => true,
        ],
        
        'permissions' => [
            'user' => ['manage'],
            'label' => ['manage'],
            'file' => ['manage'],
        ],
    ],
];