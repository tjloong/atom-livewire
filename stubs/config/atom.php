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

    /**
     * Account Portal
     */
    'accounts' => [
        'verify' => true,
        'register' => true,
    ],

    /**
     * Onboarding Portal
     */
    'onboarding' => [
        'steps' => [
            'profile' => 'Personal Information',
        ],
    ],

    /**
     * App Portal
     */
    'app' => [
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
    ],
];