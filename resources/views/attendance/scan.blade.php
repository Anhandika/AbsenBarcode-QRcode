@extends('layouts.app')

@section('content')
    <div
        class="mx-auto max-w-[1080px]"
        x-data="attendanceScanner({ scanUrl: '{{ route('attendance.scan.store') }}', demoToken: '{{ $demoQrToken }}' })"
    >
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-school-purple">Pemindai mobile & hasil</div>
                <h1 class="school-display text-2xl font-semibold">Satu alur untuk memindai dan memeriksa.</h1>
                <p class="mt-1 max-w-2xl text-sm text-school-muted">Status lokasi tampil lebih dulu, lalu setiap hasil validasi tetap jelas tanpa mengandalkan warna.</p>
            </div>
            <div class="text-xs text-school-success"><span class="mr-2 inline-block h-1.5 w-1.5 rounded-full bg-school-success"></span>Contoh data uji · {{ $activeUser->name }}</div>
        </div>

        <div class="grid items-start gap-5 lg:grid-cols-[400px_minmax(0,1fr)]">
            <section class="overflow-hidden rounded-[16px] border border-school-line bg-white shadow-school-section" aria-labelledby="scanner-title">
                <div class="flex items-center justify-between bg-school-navy px-5 py-3 text-white">
                    <div class="flex items-center gap-3">
                        <div class="grid h-8 w-8 place-items-center rounded-school-control bg-white text-[10px] font-bold text-school-navy">BU</div>
                        <div><div class="school-display text-xs font-semibold">SMK BINA UTAMA</div><div class="text-[10px] text-white/55">Layar scan absensi</div></div>
                    </div>
                    <i class="ti ti-user-circle text-lg text-white/70" aria-hidden="true"></i>
                </div>

                <div class="flex items-center justify-between gap-3 border-b border-school-line px-5 py-4">
                    <div><h2 id="scanner-title" class="school-display text-lg font-semibold">{{ $activeUser->name }}</h2><p class="mt-1 text-xs text-school-muted">{{ $activeUser->role?->label() }} · {{ $activeUser->identifier }}</p></div>
                    <span class="rounded-[5px] bg-[#eeeaff] px-2 py-1 text-[10px] font-semibold text-school-purple">{{ $activeUser->class_name ?? 'Guru' }}</span>
                </div>

                <x-attendance.location-status />
                <x-attendance.scanner-frame />

                <div class="p-5">
                    <h3 class="school-display text-lg font-semibold">Pindai QR di layar sekolah</h3>
                    <p class="mt-1 text-sm text-school-muted">Posisikan seluruh QR di dalam bingkai.</p>
                    <div class="mt-4 grid gap-2">
                        <button type="button" class="school-button school-button-primary w-full" @click="startCamera" :disabled="scannerState === 'submitting' || scannerState === 'scanning'">
                            <i class="ti ti-camera" aria-hidden="true"></i>
                            <span x-text="scannerState === 'scanning' ? 'Kamera aktif…' : 'Buka kamera & pindai'">Buka kamera & pindai</span>
                        </button>
                        <button type="button" class="school-button school-button-secondary w-full" @click="locate" :disabled="locationState === 'loading'">
                            <i class="ti ti-map-pin" aria-hidden="true"></i>
                            <span x-text="locationState === 'loading' ? 'Mencari lokasi…' : 'Periksa lokasi'">Periksa lokasi</span>
                        </button>
                        <button type="button" class="text-xs font-semibold text-school-action underline decoration-school-action/30 underline-offset-4" @click="useDemoToken">Gunakan token demo untuk pengujian</button>
                    </div>
                    <div class="mt-4 rounded-school-control bg-school-canvas px-3 py-3 text-xs text-school-muted" aria-live="polite"><i class="ti ti-shield-check mr-1 text-school-purple" aria-hidden="true"></i>QR, lokasi, dan status diperiksa berurutan.</div>
                </div>
            </section>

            <section class="school-card p-5 sm:p-6" aria-labelledby="result-title">
                <div class="flex flex-col gap-4 border-b border-school-line pb-5 sm:flex-row sm:items-start sm:justify-between">
                    <div><div class="mb-2 text-[10px] font-semibold uppercase tracking-[0.17em] text-school-purple">Pemeriksaan server</div><h2 id="result-title" class="school-display text-xl font-semibold">Hasil absensi</h2><p class="mt-1 text-sm text-school-muted">Pilih state demo atau kirim hasil pemindaian ke server.</p></div>
                    <div class="rounded-school-control border border-school-line px-3 py-2 text-right"><div class="text-[10px] uppercase tracking-[0.12em] text-school-soft">Pengguna</div><div class="mt-0.5 text-xs font-semibold">{{ $activeUser->name }}</div></div>
                </div>

                <div class="mt-5"><x-attendance.validation-steps /></div>
                <div class="mt-5"><x-attendance.result-state-switcher /></div>
                <div class="mt-5 space-y-4">
                    <x-attendance.result-panel state="success" title="Kehadiran berhasil dicatat" description="Waktu kehadiran Anda telah tersimpan." label="Berhasil · validasi lengkap" icon="ti-check" tone="success" action="Selesai" />
                    <x-attendance.result-panel state="expired" title="QR sudah kedaluwarsa" description="Pindai kode terbaru yang sedang tampil di monitor." label="Gagal · token tidak aktif" icon="ti-clock-x" tone="expired" action="Pindai ulang" />
                    <x-attendance.result-panel state="outside" title="Anda berada di luar area sekolah" description="Absensi hanya dapat dilakukan di dalam radius lokasi sekolah." label="Gagal · lokasi tidak sesuai" icon="ti-map-pin-off" tone="outside" action="Periksa lokasi lagi" />
                    <x-attendance.result-panel state="duplicate" title="Kehadiran sudah tercatat" description="Anda tidak perlu melakukan pemindaian ulang hari ini." label="Ditolak · data sudah ada" icon="ti-rotate-2" tone="duplicate" action="Kembali ke beranda" />
                </div>
            </section>
        </div>
    </div>
@endsection
