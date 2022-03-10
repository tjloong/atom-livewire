<?php

return [
    'locales' => ['en'],
    'max_upload_size' => 8,
    'timezone' => 'Asia/Kuala_Lumpur',

    'login' => [
        'facebook',
        'google',
        'linkedin',
    ],

    'signups' => [
        'verify' => true,
    ],

    'onboarding' => [
        'steps' => [
            'profile' => 'Personal Information',
        ],
    ],

    'users' => [
        'data_visibility' => true,
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

    'site-settings' => [
        'settings' => [
            //
        ],
    ],
];