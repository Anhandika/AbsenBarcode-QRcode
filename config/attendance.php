<?php

return [
    'qr_ttl_seconds' => (int) env('ATTENDANCE_QR_TTL_SECONDS', 15),
    'school_radius_meters' => (int) env('ATTENDANCE_SCHOOL_RADIUS_METERS', 80),
    'timezone' => env('ATTENDANCE_TIMEZONE', 'Asia/Jakarta'),
    'qr_image_endpoint' => env('ATTENDANCE_QR_IMAGE_ENDPOINT', 'https://api.qrserver.com/v1/create-qr-code/'),
    'max_accuracy_meters' => (int) env('ATTENDANCE_MAX_ACCURACY_METERS', 150),
];
