<div class="scanner-video relative flex min-h-[300px] items-center justify-center text-white" id="qr-reader" aria-label="Area kamera pemindai QR">
    <span class="scan-corner scan-corner-tl" aria-hidden="true"></span>
    <span class="scan-corner scan-corner-tr" aria-hidden="true"></span>
    <span class="scan-corner scan-corner-bl" aria-hidden="true"></span>
    <span class="scan-corner scan-corner-br" aria-hidden="true"></span>
    <div class="relative z-10 text-center">
        <i class="ti ti-camera text-3xl text-white/70" aria-hidden="true"></i>
        <div class="mt-3 rounded-school-control bg-school-navy/80 px-3 py-2 text-xs font-semibold" x-show="scannerState !== 'scanning'">Kamera siap digunakan</div>
        <div class="mt-3 rounded-school-control bg-school-navy/80 px-3 py-2 text-xs font-semibold" x-show="scannerState === 'scanning'" x-cloak>Menunggu QR terbaca…</div>
    </div>
    <div class="absolute inset-x-0 bottom-5 z-10 text-center text-xs text-white/80">Arahkan QR pada layar sekolah</div>
</div>
