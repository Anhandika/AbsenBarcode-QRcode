<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN_SEKOLAH = 'admin_sekolah';
    case GURU = 'guru';
    case SISWA = 'siswa';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN_SEKOLAH => 'Admin sekolah',
            self::GURU => 'Guru',
            self::SISWA => 'Siswa',
        };
    }

    public function canScan(): bool
    {
        return in_array($this, [self::GURU, self::SISWA], true);
    }
}
