<?php

// Email configuration for sending emails to users
return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'auth' => true,
        'username' => 'Vetsync.01@gmail.com', // Your clinic's Gmail
        'password' => 'spcb gkth opmn yvvb',           // Your Gmail App Password
        'from_email' => 'Vetsync.01@gmail.com',
        'from_name' => 'VetSync Veterinary Clinic'
    ],
    'templates' => [
        'path' => __DIR__ . '/../templates/email/'
    ]
];
