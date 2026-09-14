<div class="grid gap-3 border-y border-school-line py-4 sm:grid-cols-4" aria-label="Status validasi" aria-live="polite">
    @foreach (['qr' => 'QR valid', 'location' => 'Lokasi sesuai', 'attendance' => 'Belum tercatat', 'recorded' => 'Pencatatan'] as $key => $fallback)
        <div class="flex items-center gap-2">
            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full border-2" :class="steps.{{ $key }}.status === 'passed' ? 'border-school-success text-school-success' : (steps.{{ $key }}.status === 'failed' ? 'border-school-danger text-school-danger' : 'border-school-line text-school-soft')">
                <i class="ti" :class="steps.{{ $key }}.status === 'passed' ? 'ti-check' : (steps.{{ $key }}.status === 'failed' ? 'ti-x' : 'ti-minus')" aria-hidden="true"></i>
            </span>
            <div class="min-w-0">
                <div class="truncate text-xs font-semibold" x-text="steps.{{ $key }}.label">{{ $fallback }}</div>
                <div class="truncate text-[10px] text-school-soft" x-text="steps.{{ $key }}.detail">Menunggu pemeriksaan</div>
            </div>
        </div>
    @endforeach
</div>
