<?php

namespace App\Enums;

enum AttendanceResult: string
{
    case SUCCESS = 'success';
    case EXPIRED = 'expired';
    case OUTSIDE_AREA = 'outside_area';
    case DUPLICATE = 'duplicate';
    case UNAVAILABLE = 'unavailable';

    public function label(): string
    {
        return match ($this) {
            self::SUCCESS => 'Tercatat',
            self::EXPIRED => 'QR kedaluwarsa',
            self::OUTSIDE_AREA => 'Di luar area',
            self::DUPLICATE => 'Sudah tercatat',
            self::UNAVAILABLE => 'Belum tersedia',
        };
    }
}
