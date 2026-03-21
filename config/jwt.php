<?php
return [
    'private_key' => storage_path(env('JWT_SECRET_KEY')),
    'public_key' => storage_path(env('JWT_PUBLIC_KEY')),
    'algorithm' => env('JWT_ALGORITHM'),

];
