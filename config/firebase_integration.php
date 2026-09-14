<?php

return [
    'enabled' => (bool) env('FIREBASE_ENABLED', false),
    'storage_enabled' => (bool) env('FIREBASE_STORAGE_ENABLED', false),
    'project_id' => env('FIREBASE_PROJECT_ID', 'anproject-8968f'),
    'credentials' => env('FIREBASE_CREDENTIALS', null),
    'firestore_collection' => env('FIREBASE_ATTENDANCE_COLLECTION', 'attendance_events'),
    'web' => [
        'auth_enabled' => (bool) env('VITE_FIREBASE_AUTH_ENABLED', false),
        'api_key' => env('VITE_FIREBASE_API_KEY'),
        'auth_domain' => env('VITE_FIREBASE_AUTH_DOMAIN'),
        'project_id' => env('VITE_FIREBASE_PROJECT_ID', env('FIREBASE_PROJECT_ID', 'anproject-8968f')),
        'storage_bucket' => env('VITE_FIREBASE_STORAGE_BUCKET'),
        'messaging_sender_id' => env('VITE_FIREBASE_MESSAGING_SENDER_ID'),
        'app_id' => env('VITE_FIREBASE_APP_ID'),
        'measurement_id' => env('VITE_FIREBASE_MEASUREMENT_ID'),
    ],
];
