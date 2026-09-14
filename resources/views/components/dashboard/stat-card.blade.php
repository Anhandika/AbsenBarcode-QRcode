@props(['stat'])

@php
    $toneClass = match ($stat['tone'] ?? 'purple') {
        'blue' => 'text-school-blue',
        'success' => 'text-school-success',
        'warning' => 'text-school-warning',
        default => 'text-school-purple',
    };
@endphp

<article class="school-card p-5">
    <div class="flex items-start justify-between gap-3">
        <span class="text-xs font-semibold text-school-muted">{{ $stat['label'] }}</span>
        <i class="ti {{ $stat['icon'] }} {{ $toneClass }} text-xl" aria-hidden="true"></i>
    </div>
    <div class="school-display mt-4 text-[30px] font-semibold tabular-nums tracking-[-0.04em]">{{ number_format($stat['value']) }}</div>
    <div class="mt-1 text-xs {{ $toneClass }}">{{ $stat['caption'] }}</div>
</article>
