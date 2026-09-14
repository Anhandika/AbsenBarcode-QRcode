<?php

namespace App\Services;

use App\Models\Attendance;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Throwable;

class FirebaseAttendancePublisher
{
    public function publish(Attendance $attendance): void
    {
        if (! config('firebase_integration.enabled')) {
            return;
        }

        $attendance->loadMissing('user', 'token');
        $database = Firebase::firestore()->database();

        $database
            ->collection(config('firebase_integration.firestore_collection'))
            ->document((string) $attendance->id)
            ->set([
                'attendanceId' => $attendance->id,
                'userId' => $attendance->user_id,
                'userName' => $attendance->user?->name,
                'identifier' => $attendance->user?->identifier,
                'role' => $attendance->user?->role?->value,
                'className' => $attendance->user?->class_name,
                'result' => $attendance->result?->value,
                'attendanceDate' => $attendance->attendance_date?->toDateString(),
                'scannedAt' => $attendance->scanned_at?->toIso8601String(),
                'latitude' => $attendance->latitude,
                'longitude' => $attendance->longitude,
                'distanceMeters' => $attendance->distance_meters,
            ], ['merge' => true]);
    }

    public function publishSafely(Attendance $attendance): void
    {
        try {
            $this->publish($attendance);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
