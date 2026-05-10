<?php

use App\Models\User;

return [    
    
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api_admin' => [
            'driver' => 'jwt',
            'provider' => 'users', // Merujuk ke providers.users
            'hash'     => false,
        ],
        'api_customer'  => [
            'driver'    => 'jwt',
            'provider'  => 'customers', // PERBAIKAN: Tambahkan 's' agar cocok dengan nama di bawah
            'hash'      => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'customers' => [ // Nama ini harus sama persis dengan yang dipanggil di guards
            'driver'    => 'eloquent',
            'model'     => App\Models\Customer::class,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
