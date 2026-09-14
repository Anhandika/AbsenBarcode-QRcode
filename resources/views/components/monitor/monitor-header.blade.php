<header class="flex min-h-[62px] items-center justify-between gap-4 bg-school-navy px-5 py-3 text-white sm:px-7">
    <div class="flex items-center gap-3">
        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-school-control bg-white text-xs font-bold text-school-navy">BU</div>
        <div>
            <div class="school-display text-xs font-semibold">SMK BINA UTAMA KENDAL</div>
            <div class="text-[11px] text-white/55">Layar absensi pusat · ruang utama</div>
        </div>
    </div>
    <div class="flex items-center gap-3 text-xs text-white/70 sm:gap-5">
        <span class="hidden items-center gap-2 sm:flex"><span class="h-2 w-2 rounded-full bg-[#4ee0c2]"></span>Terhubung</span>
        <span class="tabular-nums text-white">{{ now(config('attendance.timezone'))->format('H:i:s') }}</span>
    </div>
</header>
