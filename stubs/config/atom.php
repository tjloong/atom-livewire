<?php

return [
    'locales' => ['en'],
    'max_upload_size' => 8,
    'timezone' => 'Asia/Kuala_Lumpur',

    'auth' => [
        'register' => true,
        'forgot-password' => true,
        'verify' => true,
    ],

    'labels' => [
        'blog-category',
    ],
    
    'permissions' => [
        'users' => ['manage'],
        'labels' => ['manage'],
        'files' => ['manage'],
        'site-settings' => ['manage'],
    ],

    'data_visibility' => true,
];