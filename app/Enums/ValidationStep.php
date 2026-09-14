<?php

namespace App\Enums;

enum ValidationStep: string
{
    case QR = 'qr';
    case LOCATION = 'location';
    case ATTENDANCE = 'attendance';
    case RECORDED = 'recorded';

    public function label(): string
    {
        return match ($this) {
            self::QR => 'QR valid',
            self::LOCATION => 'Lokasi sesuai',
            self::ATTENDANCE => 'Status kehadiran',
            self::RECORDED => 'Pencatatan',
        };
    }
}
