@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-[1280px]">
        <div class="mb-6 flex flex-col gap-4 border-b border-school-line pb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-school-purple">
                    <span class="h-1.5 w-1.5 rounded-full bg-school-purple"></span>
                    Variant 1 · Digital Plinth
                </div>
                <h1 class="school-display text-2xl font-semibold sm:text-[25px]">Absensi hari ini</h1>
                <p class="mt-1 text-sm text-school-muted">Pantau QR dinamis, lokasi, dan catatan kehadiran SMK BINA UTAMA KENDAL.</p>
            </div>
            <div class="flex items-center gap-2 rounded-school-control border border-school-line bg-white px-3.5 py-2.5 text-xs text-school-muted">
                <i class="ti ti-layout-grid text-base text-school-purple" aria-hidden="true"></i>
                04 tampilan utama
            </div>
        </div>

        <section aria-labelledby="dashboard-title">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-school-purple">{{ $todayLabel }}</div>
                    <h2 id="dashboard-title" class="school-display text-2xl font-semibold tracking-[-0.04em] sm:text-[25px]">Pusat absensi sekolah</h2>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-2 rounded-school-control border border-school-line bg-white px-3 py-2 text-xs font-semibold text-school-muted">
                        <i class="ti ti-qrcode text-base text-school-success" aria-hidden="true"></i>
                        Layar QR aktif
                    </div>
                    <div class="flex items-center gap-2 rounded-school-control border border-school-line bg-white px-3 py-2 text-xs font-semibold text-school-muted">
                        <i class="ti ti-map-pin-check text-base text-school-success" aria-hidden="true"></i>
                        Lokasi sekolah siap
                    </div>
                    <a href="{{ route('monitor') }}" class="school-button school-button-primary">Buka layar QR <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            </div>

            <div class="grid gap-3 grid-cols-2 xl:grid-cols-12">
                <div class="col-span-1 xl:col-span-4"><x-dashboard.stat-card :stat="$stats[0]" /></div>
                <div class="col-span-1 xl:col-span-4"><x-dashboard.stat-card :stat="$stats[1]" /></div>
                <div class="col-span-1 xl:col-span-2"><x-dashboard.stat-card :stat="$stats[2]" /></div>
                <div class="col-span-1 xl:col-span-2"><x-dashboard.stat-card :stat="$stats[3]" /></div>
            </div>

            <div class="mt-5 grid gap-5 lg:grid-cols-[minmax(0,1fr)_245px]">
                <x-dashboard.recent-scans :scans="$recentScans" :school="$school" />
                <x-attendance.validation-sequence />
            </div>
        </section>

        <footer class="mt-8 flex flex-col gap-2 border-t border-school-line pt-4 text-xs text-school-soft sm:flex-row sm:items-center sm:justify-between">
            <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-school-success"></span>QR · lokasi · status kehadiran · pencatatan</span>
            <span class="tabular-nums">SMK BINA UTAMA KENDAL · {{ now(config('attendance.timezone'))->format('H:i') }} WIB</span>
        </footer>
    </div>
@endsection
