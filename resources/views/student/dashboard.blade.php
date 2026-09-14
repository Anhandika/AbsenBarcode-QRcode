@extends('layouts.app')
@section('content')
<div class="max-w-[1200px] mx-auto">
    {{-- Header with logo and user info --}}
    <div class="mb-6 animate-[fadeIn_.6s_ease]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Bina Utama" class="h-10 w-10 rounded-xl bg-white p-1.5 shadow-sm border border-school-line">
                <div>
                    <h1 class="school-display text-xl font-bold text-[#0f1e3d]">SMK BINA UTAMA KENDAL</h1>
                    <p class="text-xs text-[#68748b]">Absen Digital - {{ auth()->user()->role->label() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 sm:ml-auto">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-semibold text-[#172033]">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-[#68748b]">{{ auth()->user()->identifier }} • {{ auth()->user()->class_name ?? 'Guru' }}</p>
                </div>
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#1a3a7a] to-[#2c68f5] flex items-center justify-center text-white font-bold text-xl shadow-[0_8px_20px_rgba(44,104,245,.35)]">
                    {{ str(auth()->user()->name)->substr(0,1)->upper() }}
                </div>
            </div>
        </div>

        {{-- 3 Tab Navigation --}}
        <nav class="flex gap-2 bg-white rounded-2xl p-1.5 border border-school-line shadow-sm" role="tablist">
            <a href="{{ route('student.dashboard') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('student.dashboard') ? 'bg-gradient-to-r from-[#1a3a7a] to-[#2c68f5] text-white shadow-[0_4px_12px_rgba(44,104,245,.35)]' : 'text-[#68748b] hover:bg-[#f2f5fa]' }}">
                <i class="ti ti-calendar-event"></i> Absensi
            </a>
            <a href="{{ route('attendance.scan') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('attendance.scan*') ? 'bg-gradient-to-r from-[#1a3a7a] to-[#2c68f5] text-white shadow-[0_4px_12px_rgba(44,104,245,.35)]' : 'text-[#68748b] hover:bg-[#f2f5fa]' }}">
                <i class="ti ti-qrcode"></i> Layar Absen
            </a>
            <a href="{{ route('student.profile') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm transition {{ request()->routeIs('student.profile') ? 'bg-gradient-to-r from-[#1a3a7a] to-[#2c68f5] text-white shadow-[0_4px_12px_rgba(44,104,245,.35)]' : 'text-[#68748b] hover:bg-[#f2f5fa]' }}">
                <i class="ti ti-settings"></i> Pengaturan
            </a>
        </nav>
    </div>

    @if(session('ok'))
    <div class="mb-4 animate-[slideIn_.4s_ease] rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center justify-between">
        {{ session('ok') }}
        <button @click="$el.parentElement.remove()" class="text-green-500 hover:text-green-700"><i class="ti ti-x"></i></button>
    </div>
    @endif

    {{-- ABSENSI CONTENT --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
        {{-- Today Status Card --}}
        <div class="xl:col-span-2 relative overflow-hidden rounded-2xl bg-white border border-school-line p-5 shadow-sm animate-[fadeInUp_.5s_ease]">
            <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-gradient-to-br from-[#2c68f5]/15 to-[#ffd500]/15 blur"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#623ed8]">Absensi Hari Ini</p>
                    <h2 class="school-display mt-1 text-2xl font-bold text-[#0f1e3d]">{{ ucfirst($stats['today']['status']) }}</h2>
                    <p class="mt-1 text-sm text-[#68748b]">Status kehadiran pada {{ now(config('attendance.timezone'))->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
                @php
                    $statusColors = [
                        'success' => ['bg-green-100 text-green-700', 'ti-circle-check'],
                        'late' => ['bg-yellow-100 text-yellow-700', 'ti-clock'],
                        'outside_area' => ['bg-red-100 text-red-700', 'ti-map-pin-off'],
                        'expired' => ['bg-gray-100 text-gray-700', 'ti-clock-x'],
                        'duplicate' => ['bg-blue-100 text-blue-700', 'ti-rotate-2'],
                        'belum_absen' => ['bg-gray-100 text-gray-500', 'ti-clock-pause'],
                    ];
                    $sc = $statusColors[$stats['today']['status']] ?? $statusColors['belum_absen'];
                @endphp
                <div class="flex flex-col items-end gap-2">
                    <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $sc[0] }} flex items-center gap-1">
                        <i class="ti {{ $sc[1] }}"></i> {{ ucfirst($stats['today']['status']) }}
                    </span>
                    <p class="text-xs text-[#8a95a8]">Jam: <span class="font-mono font-bold text-[#172033]">{{ $stats['today']['time'] }}</span></p>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-3 pt-4 border-t border-school-line">
                <div class="text-center">
                    <p class="school-display text-xl font-bold text-[#2c68f5]">{{ $stats['month']['hadir'] }}</p>
                    <p class="text-[10px] text-[#8a95a8]">Hadir</p>
                </div>
                <div class="text-center">
                    <p class="school-display text-xl font-bold text-[#ffd500]">{{ $stats['month']['terlambat'] }}</p>
                    <p class="text-[10px] text-[#8a95a8]">Terlambat</p>
                </div>
                <div class="text-center">
                    <p class="school-display text-xl font-bold text-[#c54551]">{{ $stats['month']['di_luar'] }}</p>
                    <p class="text-[10px] text-[#8a95a8]">Di Luar</p>
                </div>
            </div>
        </div>

        {{-- Monthly Recap Cards --}}
        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line p-4 shadow-sm animate-[fadeInUp_.5s_ease_.1s]">
            <div class="absolute -top-2 -right-2 h-16 w-16 rounded-full bg-gradient-to-br from-[#167a67]/15 to-[#2cf0b8]/15 blur"></div>
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#623ed8]">Rekap 1 Bulan</p>
            <p class="school-display mt-2 text-3xl font-bold text-[#0f1e3d]">{{ $stats['month']['total'] }}</p>
            <p class="mt-1 text-sm text-[#68748b]">Total absensi bulan ini</p>
            <div class="mt-3 flex justify-between text-xs">
                <span class="text-[#8a95a8]">Dari {{ now()->locale('id')->translatedFormat('F Y') }}</span>
                <span class="font-semibold text-[#172033]">{{ $stats['month']['hadir'] }}/{{ $stats['month']['total'] }}</span>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line p-4 shadow-sm animate-[fadeInUp_.5s_ease_.2s]">
            <div class="absolute -top-2 -right-2 h-16 w-16 rounded-full bg-gradient-to-br from-[#c54551]/15 to-[#ff6b6b]/15 blur"></div>
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#623ed8]">Persentase</p>
            <p class="school-display mt-2 text-3xl font-bold text-[#0f1e3d]">
                @php $pct = $stats['month']['total'] > 0 ? round(($stats['month']['hadir'] / $stats['month']['total']) * 100) : 0; @endphp
                {{ $pct }}%
            </p>
            <p class="mt-1 text-sm text-[#68748b]">Kehadiran berhasil</p>
            <div class="mt-3 h-2 bg-school-line rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#167a67] to-[#2cf0b8] rounded-full transition-all" style="width: {{ $pct }}%"></div>
            </div>
        </div>
    </div>

    {{-- Recent Scans --}}
    <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm animate-[fadeInUp_.5s_ease_.3s]">
        <div class="absolute -top-2 -right-2 h-20 w-20 rounded-full bg-gradient-to-br from-[#623ed8]/15 to-[#8ea8ff]/15 blur"></div>
        <div class="p-4 sm:p-5 border-b border-school-line flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#623ed8]">Riwayat Terbaru</p>
                <h3 class="school-display text-lg font-bold text-[#0f1e3d]">5 pemindaian terakhir</h3>
            </div>
        </div>
        <div class="p-4 sm:p-5">
            @if($recentScans->isEmpty())
                <div class="text-center py-8">
                    <i class="ti ti-history text-4xl text-[#e3e8f0]"></i>
                    <p class="mt-2 text-sm text-[#8a95a8]">Belum ada riwayat absensi</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentScans as $scan)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-school-canvas hover:bg-white/80 transition border border-school-line/50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1a3a7a] to-[#2c68f5] flex items-center justify-center text-white font-bold">
                                {{ str($scan->user->name)->substr(0,1)->upper() }}
                            </div>
                            <div>
                                <p class="font-semibold text-[#172033]">{{ $scan->user->name }}</p>
                                <p class="text-xs text-[#68748b]">{{ $scan->attendance_date }} • {{ $scan->scanned_at?->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @php
                                $resultClass = [
                                    'success' => 'bg-green-100 text-green-700',
                                    'late' => 'bg-yellow-100 text-yellow-700',
                                    'outside_area' => 'bg-red-100 text-red-700',
                                    'expired' => 'bg-gray-100 text-gray-700',
                                    'duplicate' => 'bg-blue-100 text-blue-700',
                                ][$scan->result->value] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $resultClass }}">{{ ucfirst($scan->result->value) }}</span>
                            <span class="text-xs text-[#8a95a8]">{{ $scan->distance_meters ? round($scan->distance_meters) . 'm' : '-' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<style>
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideIn { from{opacity:0;transform:translateX(-12px)} to{opacity:1;transform:translateX(0)} }
.animate-\[fadeIn_\.6s_ease\] { animation: fadeIn .6s ease both; }
.animate-\[fadeInUp_\.5s_ease\] { animation: fadeInUp .5s ease both; }
.animate-\[fadeInUp_\.5s_ease_\.1s\] { animation: fadeInUp .5s ease .1s both; }
.animate-\[fadeInUp_\.5s_ease_\.2s\] { animation: fadeInUp .5s ease .2s both; }
.animate-\[fadeInUp_\.5s_ease_\.3s\] { animation: fadeInUp .5s ease .3s both; }
.animate-\[slideIn_\.4s_ease\] { animation: slideIn .4s ease both; }
</style>
@endpush