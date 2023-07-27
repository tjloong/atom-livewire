<?php

return [
    'locales' => ['en'],
    'max_upload_size' => 8,
    'timezone' => 'Asia/Kuala_Lumpur',
    'payment_gateway' => [],

    // this will enable the hsts header in every request
    'hsts' => false,
    
    // explicitly set the allowed hosts to prevent host poisoning 
    'allowed_hosts' => [],

    // auth
    'auth' => [
        'verify' => true,
        'register' => true,
        'login' => [
            'email',
            // 'username',
            // 'google',
            // 'facebook',
            // 'linkedin', 
        ],
    ],

    // custom polymorphic types
    'morph_map' => [
        'ticket' => 'Jiannius\Atom\Models\Ticket',
    ],
];