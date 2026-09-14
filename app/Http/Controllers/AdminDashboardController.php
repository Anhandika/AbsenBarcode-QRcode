<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceResult;
use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\SchoolSetting;
use App\Models\User;
use App\Support\AttendancePresenter;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $school = SchoolSetting::query()->firstOrFail();
        $today = now($school->timezone);
        $studentQuery = User::query()->where('role', UserRole::SISWA->value)->where('active', true);
        $presentStudents = Attendance::query()
            ->whereDate('attendance_date', $today->toDateString())
            ->where('result', AttendanceResult::SUCCESS->value)
            ->whereHas('user', fn ($query) => $query->where('role', UserRole::SISWA->value))
            ->count();
        $totalStudents = (clone $studentQuery)->count();

        return view('admin.dashboard', [
            'school' => $school,
            'activeUser' => auth()->user(),
            'stats' => [
                ['label' => 'Total siswa', 'value' => $totalStudents, 'caption' => 'data pengguna aktif', 'icon' => 'ti-school', 'tone' => 'purple'],
                ['label' => 'Total guru', 'value' => User::query()->where('role', UserRole::GURU->value)->where('active', true)->count(), 'caption' => 'data pengguna aktif', 'icon' => 'ti-users', 'tone' => 'blue'],
                ['label' => 'Hadir hari ini', 'value' => $presentStudents, 'caption' => AttendancePresenter::percentage($presentStudents, $totalStudents) . ' dari total', 'icon' => 'ti-circle-check', 'tone' => 'success'],
                ['label' => 'Belum hadir', 'value' => max(0, $totalStudents - $presentStudents), 'caption' => 'menunggu', 'icon' => 'ti-clock', 'tone' => 'warning'],
            ],
            'recentScans' => Attendance::query()->with('user')->latest('scanned_at')->limit(5)->get(),
            'todayLabel' => ucfirst($today->locale('id')->translatedFormat('l, d F Y')) . ' · ' . $today->format('H:i') . ' WIB',
            'summary' => [
                'present' => $presentStudents,
                'absent' => max(0, $totalStudents - $presentStudents),
                'percentage' => AttendancePresenter::percentage($presentStudents, $totalStudents),
            ],
        ]);
    }
}
