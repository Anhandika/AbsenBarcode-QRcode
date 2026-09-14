<?php

namespace App\Support;

use App\Enums\AttendanceResult;
use App\Enums\UserRole;
use DateTimeInterface;

final class AttendancePresenter
{
    public static function role(UserRole|string|null $role): string
    {
        $role = $role instanceof UserRole ? $role : UserRole::tryFrom((string) $role);

        return $role?->label() ?? 'Pengguna';
    }

    public static function result(AttendanceResult|string|null $result): string
    {
        $result = $result instanceof AttendanceResult ? $result : AttendanceResult::tryFrom((string) $result);

        return $result?->label() ?? 'Belum tersedia';
    }

    public static function time(DateTimeInterface|string|null $time, string $timezone = 'Asia/Jakarta'): string
    {
        if (! $time) {
            return '—';
        }

        $date = $time instanceof DateTimeInterface ? $time : new \DateTimeImmutable($time);
        $date = $date->setTimezone(new \DateTimeZone($timezone));

        return $date->format('H:i') . ' WIB';
    }

    public static function dateTime(DateTimeInterface|string|null $time, string $timezone = 'Asia/Jakarta'): string
    {
        if (! $time) {
            return '—';
        }

        $date = $time instanceof DateTimeInterface ? $time : new \DateTimeImmutable($time);
        $date = $date->setTimezone(new \DateTimeZone($timezone));

        return $date->format('d M Y · H:i') . ' WIB';
    }

    public static function percentage(int $present, int $total): string
    {
        if ($total === 0) {
            return '0%';
        }

        return (string) round(($present / $total) * 100) . '%';
    }

    public static function countdown(int $seconds): string
    {
        return str_pad((string) max(0, $seconds), 2, '0', STR_PAD_LEFT);
    }
}
