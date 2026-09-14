<?php

namespace Tests\Feature;

use App\Enums\AttendanceResult;
use App\Models\Attendance;
use App\Models\AttendanceToken;
use App\Models\SchoolSetting;
use App\Models\User;
use App\Services\QrTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AttendanceScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_record_attendance_inside_school_radius(): void
    {
        $this->seed();
        $student = User::where('email', 'siswa@example.test')->firstOrFail();
        $school = SchoolSetting::firstOrFail();
        $token = app(QrTokenService::class)->issue($school);

        $response = $this->actingAs($student)->postJson(route('attendance.scan.store'), [
            'qr_token' => $token->plain_token,
            'latitude' => $school->latitude,
            'longitude' => $school->longitude,
            'accuracy' => 15,
        ]);

        $response->assertOk()->assertJsonPath('result', AttendanceResult::SUCCESS->value);
        $this->assertDatabaseHas('attendances', ['user_id' => $student->id, 'result' => AttendanceResult::SUCCESS->value]);
    }

    public function test_expired_qr_is_rejected(): void
    {
        $this->seed();
        $student = User::where('email', 'siswa@example.test')->firstOrFail();
        $school = SchoolSetting::firstOrFail();
        $token = AttendanceToken::create([
            'school_setting_id' => $school->id,
            'token_hash' => hash('sha256', 'expired-demo-token'),
            'issued_at' => Carbon::now()->subMinute(),
            'expires_at' => Carbon::now()->subSecond(),
            'active' => true,
        ]);

        $this->actingAs($student)->postJson(route('attendance.scan.store'), [
            'qr_token' => 'expired-demo-token',
            'latitude' => $school->latitude,
            'longitude' => $school->longitude,
        ])->assertStatus(422)->assertJsonPath('result', AttendanceResult::EXPIRED->value);

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_duplicate_attendance_is_rejected(): void
    {
        $this->seed();
        $student = User::where('email', 'siswa@example.test')->firstOrFail();
        $school = SchoolSetting::firstOrFail();
        $token = app(QrTokenService::class)->issue($school);
        Attendance::create([
            'user_id' => $student->id,
            'attendance_token_id' => $token->id,
            'attendance_date' => now($school->timezone)->toDateString(),
            'scanned_at' => now($school->timezone),
            'latitude' => $school->latitude,
            'longitude' => $school->longitude,
            'distance_meters' => 0,
'result' => AttendanceResult::SUCCESS->value,
        ]);

        $this->actingAs($student)->postJson(route('attendance.scan.store'), [
            'qr_token' => $token->plain_token,
            'latitude' => $school->latitude,
            'longitude' => $school->longitude,
        ])->assertStatus(422)->assertJsonPath('result', AttendanceResult::DUPLICATE->value);
    }
}
