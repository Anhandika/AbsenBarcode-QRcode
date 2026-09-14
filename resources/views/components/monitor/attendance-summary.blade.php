@props(['summary'])

<div class="border-t border-school-line pt-5">
    <div class="mb-4 text-[10px] font-semibold uppercase tracking-[0.16em] text-school-soft">Ringkasan hari ini</div>
    <div class="grid grid-cols-3 gap-4">
        <div><div class="school-display text-2xl font-semibold tabular-nums">{{ $summary['present'] }}</div><div class="mt-1 text-xs text-school-muted">Hadir</div></div>
        <div><div class="school-display text-2xl font-semibold tabular-nums">{{ $summary['absent'] }}</div><div class="mt-1 text-xs text-school-muted">Belum hadir</div></div>
        <div><div class="school-display text-2xl font-semibold tabular-nums">{{ $summary['percentage'] }}</div><div class="mt-1 text-xs text-school-muted">Persentase</div></div>
    </div>
</div>
