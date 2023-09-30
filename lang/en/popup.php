<?php

return [
    'confirm' => [
        'trash' => [
            'title' => 'Move to Trash',
            'message' => 'Are you sure to move the record to trash? You can restore it later.|Are you sure to move :count records to trash? You can restore it later.',
        ],
        'clear-trashed' => [
            'title' => 'Clear All Trashed',
            'message' => 'This will PERMANENTLY DELETE ALL THE SELECTED RECORDS! Are you sure?',
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
