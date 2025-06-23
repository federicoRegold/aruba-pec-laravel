<?php

return [
    'config' => [
        'host' => env('ARUBA_PEC_HOST'),
        'port' => env('ARUBA_PEC_PORT'),
        'encryption' => env('ARUBA_PEC_ENCRYPTION'),
    ],
    'accounts' => [
        'default' => [
            'username' => env('ARUBA_PEC_USERNAME'),
            'password' => env('ARUBA_PEC_PASSWORD'),
            'from' => [
                'address' => env('ARUBA_PEC_FROM_ADDRESS'),
                'name' => env('ARUBA_PEC_FROM_NAME'),
            ],
        ],
    ]
];