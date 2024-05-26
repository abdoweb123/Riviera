<?php

return [

    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'admins',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'driver' => [
            'driver' => 'session',
            'provider' => 'drivers',
        ],
        'client' => [
            'driver' => 'session',
            'provider' => 'clients',
        ],

    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'drivers' => [
            'provider' => 'drivers',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'clients' => [
            'provider' => 'clients',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => \Modules\Admin\Entities\Model::class,
        ],
        'drivers' => [
            'driver' => 'eloquent',
            'model' => \Modules\Driver\Entities\Model::class,
        ],
        'clients' => [
            'driver' => 'eloquent',
            'model' => \Modules\Client\Entities\Model::class,
        ],

    ],

    'password_timeout' => 10800,

];
