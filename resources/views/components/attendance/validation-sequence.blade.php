<section class="school-card h-full p-5" aria-labelledby="validation-sequence-title">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 id="validation-sequence-title" class="school-display text-base font-semibold">Urutan validasi</h3>
            <p class="mt-1 text-xs text-school-soft">Pemeriksaan setiap pemindaian</p>
        </div>
        <i class="ti ti-route text-lg text-school-purple" aria-hidden="true"></i>
    </div>

    <ol class="relative mt-6 space-y-5 before:absolute before:left-3 before:top-2 before:h-[calc(100%-1rem)] before:w-px before:bg-school-line">
        @foreach ([['icon' => 'ti-circle-check', 'title' => 'QR valid', 'detail' => 'Token masih aktif', 'tone' => 'success'], ['icon' => 'ti-map-pin-check', 'title' => 'Lokasi sesuai', 'detail' => 'Dalam radius 80 m', 'tone' => 'success'], ['icon' => 'ti-user-check', 'title' => 'Status kehadiran', 'detail' => 'Belum tercatat', 'tone' => 'success'], ['icon' => 'ti-database', 'title' => 'Pencatatan', 'detail' => 'Menunggu proses', 'tone' => 'muted']] as $step)
            <li class="relative flex gap-3">
                <span class="z-10 grid h-6 w-6 shrink-0 place-items-center rounded-full border-2 bg-white text-xs {{ $step['tone'] === 'success' ? 'border-school-success text-school-success' : 'border-school-line text-school-soft' }}">
                    <i class="ti {{ $step['icon'] }}" aria-hidden="true"></i>
                </span>
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-school-ink">{{ $step['title'] }}</div>
                    <div class="mt-0.5 text-[11px] text-school-soft">{{ $step['detail'] }}</div>
                </div>
            </li>
        @endforeach
    </ol>
</section>
