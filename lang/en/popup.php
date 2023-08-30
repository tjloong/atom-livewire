<?php

return [
    'confirm' => [
        'trash' => [
            'title' => 'Move to Trash',
            'message' => 'Are you sure to move this record to trash? You can restore it later.',
        ],
        'delete' => [
            'title' => 'Permanently Delete Record',
            'message' => 'Are you sure to DELETE this record? This action CANNOT BE UNDONE.',
        ],
    ],
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // revenue monster
    'revmon' => [
        'test-success' => [
            'title' => 'Connection Successful',
            'message' => 'Your profile name is :profile',
        ],
        'test-failed' => [
            'title' => 'Connection Failed',
            'message' => 'Unable to establish connection with Revenu Monster. Please check your ID, secret and private key.',
        ],
        'updated' => 'Revenue Monster Updated.',
    ],
];
