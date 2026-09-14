@extends('layouts.app')
@section('content')
<div class="max-w-[1200px] mx-auto">
    {{-- Header --}}
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

    {{-- Profile Card --}}
    <div class="grid gap-5 sm:grid-cols-[320px_1fr] mb-6">
        {{-- Profile Sidebar --}}
        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm animate-[fadeInUp_.5s_ease]">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1a3a7a]/5 to-transparent"></div>
            <div class="relative p-6 sm:p-8 text-center">
                <div class="relative inline-block mb-4">
                    <div class="h-28 w-28 mx-auto rounded-2xl bg-gradient-to-br from-[#1a3a7a] to-[#2c68f5] flex items-center justify-center text-white font-bold text-4xl shadow-[0_12px_32px_rgba(44,104,245,.4)] relative z-10">
                        {{ str(auth()->user()->name)->substr(0,1)->upper() }}
                    </div>
                    <div class="absolute -bottom-2 -right-2 h-10 w-10 rounded-full bg-gradient-to-br from-[#ffd500] to-[#ffb700] flex items-center justify-center text-[#111a31] shadow-lg animate-[pulse_2s_ease-in-out_infinite]">
                        <i class="ti ti-check"></i>
                    </div>
                </div>
                <h2 class="school-display text-xl font-bold text-[#0f1e3d]">{{ auth()->user()->name }}</h2>
                <p class="mt-1 text-sm text-[#68748b]">{{ auth()->user()->role->label() }} • {{ auth()->user()->identifier }}</p>
                <p class="mt-1 text-xs text-[#8a95a8]">{{ auth()->user()->email }}</p>
                @if(auth()->user()->class_name)
                <span class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-[#f2f5fa] px-3 py-1.5 text-xs font-semibold text-[#623ed8]">
                    <i class="ti ti-school"></i> {{ auth()->user()->class_name }}
                </span>
                @endif
                <div class="mt-4 pt-4 border-t border-school-line text-xs text-[#8a95a8]">
                    <p>Anggota sejak {{ auth()->user()->created_at?->translatedFormat('F Y') }}</p>
                    <p class="mt-1">Terakhir login: {{ auth()->user()->updated_at?->diffForHumans() ?? 'baru saja' }}</p>
                </div>
            </div>
        </div>

        {{-- Settings Form --}}
        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm animate-[fadeInUp_.5s_ease_.1s]">
            <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-gradient-to-br from-[#623ed8]/10 to-[#8ea8ff]/10 blur"></div>
            <div class="p-6 sm:p-8">
                <h3 class="school-display text-lg font-bold text-[#0f1e3d] mb-1">Profil & Pengaturan</h3>
                <p class="text-sm text-[#68748b] mb-6">Kelola data akun dan preferensi aplikasi Anda.</p>

                <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-5">
                    @csrf @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-[#172033] block mb-1.5">Nama Lengkap</label>
                            <input name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10">
                            @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-[#172033] block mb-1.5">NISN / NIP</label>
                            <input name="identifier" value="{{ old('identifier', auth()->user()->identifier) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10">
                            @error('identifier')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-[#172033] block mb-1.5">Email</label>
                            <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10">
                            @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-[#172033] block mb-1.5">Kelas / Jabatan</label>
                            <input name="class_name" value="{{ old('class_name', auth()->user()->class_name) }}" class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-school-line">
                        <h4 class="school-display text-base font-bold text-[#0f1e3d] mb-3">Ubah Kata Sandi <span class="text-normal font-normal text-xs text-[#8a95a8]">(opsional)</span></h4>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-[#172033] block mb-1.5">Kata Sandi Baru</label>
                                <input name="password" type="password" class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="Minimal 6 karakter">
                                @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-[#172033] block mb-1.5">Konfirmasi Kata Sandi</label>
                                <input name="password_confirmation" type="password" class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="Ulangi kata sandi">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 h-11 rounded-xl bg-gradient-to-r from-[#1a3a7a] to-[#2c68f5] text-white font-bold text-sm shadow-[0_8px_20px_rgba(44,104,245,.35)] hover:shadow-[0_10px_28px_rgba(44,104,245,.45)] active:translate-y-[1px] transition flex items-center justify-center gap-2">
                            <i class="ti ti-device-floppy"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- FAQ / Panduan --}}
    <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm animate-[fadeInUp_.5s_ease_.2s]">
        <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-gradient-to-br from-[#ffd500]/15 to-[#ffb700]/15 blur"></div>
        <div class="p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#ffd500] to-[#ffb700] flex items-center justify-center text-[#111a31] shadow-lg">
                    <i class="ti ti-help-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="school-display text-lg font-bold text-[#0f1e3d]">Panduan & FAQ Absensi Digital</h3>
                    <p class="text-sm text-[#68748b]">Pertanyaan yang sering diajukan seputar sistem absensi SMK Bina Utama</p>
                </div>
            </div>

            <div class="space-y-3" x-data="{ open: null }">
                @foreach([
                    ['q'=>'Bagaimana cara melakukan absensi?','a'=>'Buka menu <strong>Layar Absen</strong>, pastikan GPS aktif, lalu pindai QR Code yang tampil di layar monitor sekolah. Sistem akan memvalidasi QR, lokasi (radius 80m), dan status kehadiran secara berurutan.'],
                    ['q'=>'Mengapa QR Code berubah setiap 15 detik?','a'=>'QR dinamis diperbarui setiap 15 detik untuk keamanan. Hal ini mencegah pencatatan palsu dengan screenshot QR lama. Selalu pindai QR yang sedang tampil di monitor.'],
                    ['q'=>'Absensi saya gagal dengan status "Di Luar Area", padahal saya di sekolah?','a'=>'Periksa: (1) GPS perangkat aktif & akurasi < 150m, (2) Anda berada di dalam radius 80m dari titik sekolah, (3) Coba tombol "Periksa Lokasi" di halaman scan sebelum memindai.'],
                    ['q'=>'Apa artinya status "Terlambat"?','a'=>'Absensi dicatat setelah jam masuk sekolah (sesuai pengaturan admin). Anda tetap tercatat hadir namun dengan keterangan terlambat.'],
                    ['q'=>'Bisakah absen 2 kali sehari?','a'=>'Tidak. Sistem mendeteksi duplikasi berdasarkan tanggal dan user. Jika sudah absen hari ini, pemindaian ulang akan ditolak dengan status "Duplikat".'],
                    ['q'=>'Bagaimana jika kamera tidak bisa membuka?','a'=>'Pastikan izin kamera diberikan di browser. Gunakan tombol "Gunakan token demo" untuk pengujian tanpa kamera.'],
                    ['q'=>'Apakah data absensi bisa diekspor?','a'=>'Hanya admin/guru yang bisa mengakses laporan dan ekspor CSV dari menu admin. Data siswa bersifat read-only.'],
                    ['q'=>'Kontak bantuan teknis','a'=>'Hubungi admin sekolah (IT SMK Bina Utama) atau email: <a href="mailto:it@smkbinautama.sch.id" class="text-[#2c68f5] underline">it@smkbinautama.sch.id</a>'],
                ] as $faq)
                <div class="border border-school-line/50 rounded-xl overflow-hidden bg-white/50 hover:bg-white transition">
                    <button @click="open = open === '{{ $loop->index }}' ? null : '{{ $loop->index }}'" class="w-full flex items-center justify-between p-4 text-left">
                        <span class="font-semibold text-[#172033] text-sm pr-4">{{ $faq['q'] }}</span>
                        <i class="ti ti-chevron-down text-[#8a95a8] transition-transform" :class="open === '{{ $loop->index }}' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === '{{ $loop->index }}'" x-transition class="px-4 pb-4 text-sm text-[#68748b] border-t border-school-line/50">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<style>
@keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }
.animate-\[pulse_2s_ease-in-out_infinite\] { animation: pulse 2s ease-in-out infinite; }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideIn { from{opacity:0;transform:translateX(-12px)} to{opacity:1;transform:translateX(0)} }
.animate-\[fadeIn_\.6s_ease\] { animation: fadeIn .6s ease both; }
.animate-\[fadeInUp_\.5s_ease\] { animation: fadeInUp .5s ease both; }
.animate-\[fadeInUp_\.5s_ease_\.1s\] { animation: fadeInUp .5s ease .1s both; }
.animate-\[fadeInUp_\.5s_ease_\.2s\] { animation: fadeInUp .5s ease .2s both; }
.animate-\[slideIn_\.4s_ease\] { animation: slideIn .4s ease both; }
</style>
@endpush