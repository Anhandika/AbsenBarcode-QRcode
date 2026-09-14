<div class="flex items-center justify-between gap-3 border-y border-school-line px-4 py-3 text-xs" aria-live="polite">
    <div class="flex min-w-0 items-center gap-2" :class="locationState === 'ready' ? 'text-school-success' : (locationState === 'error' ? 'text-school-danger' : 'text-school-muted')">
        <i class="ti" :class="locationState === 'ready' ? 'ti-map-pin-check' : (locationState === 'error' ? 'ti-map-pin-off' : 'ti-map-pin')" aria-hidden="true"></i>
        <span x-text="locationMessage">Aktifkan GPS untuk melanjutkan absensi.</span>
    </div>
    <span class="shrink-0 text-[10px] font-semibold uppercase tracking-[0.12em] text-school-soft" x-text="locationState === 'ready' ? 'GPS siap' : 'GPS belum siap'">GPS belum siap</span>
</div>
