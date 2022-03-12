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

    'accounts' => [
        'verify' => true,
        'sidenavs' => [
            'profile' => 'Profile',
        ],
    ],

    'onboarding' => [
        'steps' => [
            'profile' => 'Personal Information',
        ],
    ],

    'users' => [
        'data_visibility' => true,
    ],

    'blogs' => [
        'sidenavs' => [
            'content' => 'Content',
            'seo' => 'SEO',
            'settings' => 'Settings',
        ],
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
        'sidenavs' => [
            //
        ],
    ],
];