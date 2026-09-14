<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceToken extends Model
{
    protected $fillable = [
        'school_setting_id',
        'token_hash',
        'issued_at',
        'expires_at',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expires_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(SchoolSetting::class, 'school_setting_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function isValid(): bool
    {
        return $this->active && $this->expires_at->isFuture();
    }
}
