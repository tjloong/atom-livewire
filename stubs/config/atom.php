<?php

return [
    'locales' => ['en'],
    'max_upload_size' => 8,
    'timezone' => 'Asia/Kuala_Lumpur',
    'payment_gateway' => [],

    /**
     * This will enable the hsts header in every request
     */
    'hsts' => false,
    
    /**
     * Explicitly set the allowed hosts to prevent host poisoning 
     */
    'allowed_hosts' => [],

    /**
     * Auth Portal
     */
    'auth' => [
        'verify' => true,
        'register' => true,
        'login' => [
            'google',
            'facebook',
            'linkedin',    
        ],
    ],
];