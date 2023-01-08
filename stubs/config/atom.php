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
     * Account Portal
     */
    'accounts' => [
        'verify' => true,
        'register' => true,
        'login' => [
            'google',
            'facebook',
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
            'root' => [],
            'signup' => [
                'setting' => ['manage'],
                'preference' => ['manage'],
            ],
        ],

        'document' => [
            'types' => [
                'quotation', 
                'sales-order', 
                'delivery-order', 
                'invoice', 
                'purchase-order', 
                'bill',
            ],
        ],
    ],
];