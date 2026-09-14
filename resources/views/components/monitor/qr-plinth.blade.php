@props(['qr'])

<div class="qr-plinth mx-auto w-full max-w-[430px]">
    <div class="relative rounded-[14px] bg-white p-5 shadow-school-plinth sm:p-6">
        <div class="flex aspect-square items-center justify-center rounded-[10px] bg-white p-2">
            <img
                :src="qr.qr_url"
                src="{{ $qr['qr_url'] }}"
                alt="QR absensi dinamis SMK BINA UTAMA KENDAL"
                width="520"
                height="520"
                decoding="async"
                loading="lazy"
                class="aspect-square w-full object-contain"
            >
        </div>
        <div class="mt-4 flex items-center justify-between gap-3 border-t border-school-line pt-3 text-[11px] text-school-muted">
            <span class="flex items-center gap-1.5"><i class="ti ti-refresh" aria-hidden="true"></i>Token berganti otomatis</span>
            <span class="font-semibold tabular-nums text-school-ink" x-text="qr.id ? `#${String(qr.id).padStart(4, '0')}` : '#—'">#{{ str_pad((string) ($qr['id'] ?? 0), 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>
</div>
