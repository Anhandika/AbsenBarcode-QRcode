<?php
namespace App\Http\Controllers;

use App\Enums\AttendanceResult;
use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $school = SchoolSetting::firstOrFail();
        $today = now($school->timezone)->toDateString();
        $monthStart = now($school->timezone)->startOfMonth()->toDateString();
        $monthEnd = now($school->timezone)->endOfMonth()->toDateString();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $monthAttendances = Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->orderBy('attendance_date', 'desc')
            ->get();

        $stats = [
            'today' => [
                'status' => $todayAttendance?->result->value ?? 'belum_absen',
                'time' => $todayAttendance?->scanned_at?->format('H:i') ?? '-',
                'distance' => $todayAttendance?->distance_meters ? round($todayAttendance->distance_meters) . 'm' : '-',
            ],
            'month' => [
                'total' => $monthAttendances->count(),
                'hadir' => $monthAttendances->where('result', AttendanceResult::SUCCESS->value)->count(),
                        'terlambat' => $monthAttendances->whereNot('result', AttendanceResult::SUCCESS->value)->whereNot('result', AttendanceResult::OUTSIDE_AREA->value)->count(),
                'di_luar' => $monthAttendances->where('result', AttendanceResult::OUTSIDE_AREA->value)->count(),
            ],
        ];

        $recentScans = Attendance::where('user_id', $user->id)
            ->with('token.school')
            ->latest('scanned_at')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact('user', 'school', 'stats', 'recentScans'));
    }

    public function scan(): View
    {
        $school = SchoolSetting::firstOrFail();
        $token = app(\App\Services\QrTokenService::class)->activeOrIssue($school);
        return view('attendance.scan', [
            'demoQrToken' => $token->getAttribute('plain_token'),
            'activeUser' => auth()->user(),
        ]);
    }

    public function profile(): View
    {
        $user = auth()->user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'identifier' => 'required|string|max:30|unique:users,identifier,' . $user->id,
            'class_name' => 'nullable|string|max:50',
            'password' => 'nullable|min:6|confirmed',
        ]);
        if (empty($data['password'])) unset($data['password']);
        $user->update($data);
        return back()->with('ok', 'Profil diperbarui');
    }
}