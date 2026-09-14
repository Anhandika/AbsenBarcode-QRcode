<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceResult;
use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\SchoolSetting;
use App\Models\User;
use App\Services\QrTokenService;
use App\Support\AttendancePresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function __construct(private readonly QrTokenService $tokens)
    {
    }

    public function index(): View
    {
        $school = SchoolSetting::query()->firstOrFail();
        $token = $this->tokens->activeOrIssue($school);
        return view('monitor.index', [
            'school' => $school,
            'activeQr' => $this->tokens->payload($token),
            'summary' => $this->summary($school),
        ]);
    }

    public function refresh(): JsonResponse
    {
        $school = SchoolSetting::query()->firstOrFail();
        // only rotate if expired, otherwise return active
        $active = \App\Models\AttendanceToken::query()->where('school_setting_id',$school->id)->where('active',true)->latest('issued_at')->first();
        $token = ($active && $active->isValid()) ? $active : $this->tokens->issue($school);
        // if reusing active without plain_token, must issue new one (plain not stored)
        if (!$token->getAttribute('plain_token')) $token = $this->tokens->issue($school);
        return response()->json(['ok'=>true,'qr'=>$this->tokens->payload($token)]);
    }

    public function recentScans(): JsonResponse
    {
        $school = SchoolSetting::query()->firstOrFail();
        $today = now($school->timezone)->toDateString();
        $scans = Attendance::with('user')->whereDate('attendance_date',$today)->latest('scanned_at')->limit(10)->get()->map(fn($a)=>[
            'id'=>$a->id,'name'=>$a->user->name,'identifier'=>$a->user->identifier,'class'=>$a->user->class_name,'time'=>$a->scanned_at?->format('H:i'),'result'=>$a->result->value,
        ]);
        return response()->json(['scans'=>$scans,'summary'=>$this->summary($school)]);
    }

    /** @return array<string, int|string> */
    private function summary(SchoolSetting $school): array
    {
        $today = now($school->timezone)->toDateString();
        $total = User::query()->where('role', UserRole::SISWA->value)->where('active', true)->count();
        $present = Attendance::query()
            ->whereDate('attendance_date', $today)
            ->where('result', AttendanceResult::SUCCESS->value)
            ->whereHas('user', fn ($query) => $query->where('role', UserRole::SISWA->value))
            ->count();

        return [
            'present' => $present,
            'absent' => max(0, $total - $present),
            'percentage' => AttendancePresenter::percentage($present, $total),
        ];
    }
}
