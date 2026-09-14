@props(['state', 'title', 'description', 'label', 'icon', 'tone', 'action'])

@php
    $panelClass = match ($tone) {
        'success' => 'result-panel-success',
        'expired' => 'result-panel-expired',
        'outside' => 'result-panel-outside',
        default => 'result-panel-duplicate',
    };
    $toneClass = match ($tone) {
        'success' => 'text-school-success border-school-success',
        'expired' => 'text-school-warning border-school-warning',
        'outside' => 'text-school-danger border-school-danger',
        default => 'text-school-muted border-school-ink',
    };
@endphp

@php
    $actionCall = $state === 'outside' ? 'resetForScan(); locate()' : 'resetForScan()';
@endphp

<section class="result-panel {{ $panelClass }}" x-show="result === '{{ $state }}'" x-cloak role="tabpanel" aria-live="polite">
    <div class="flex items-start justify-between gap-4">
        <div class="flex gap-4">
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full border-2 {{ $toneClass }} text-[22px]">
                <i class="ti {{ $icon }}" aria-hidden="true"></i>
            </div>
            <div>
                <div class="mb-1 text-[10px] font-semibold uppercase tracking-[0.14em] {{ $toneClass }}">{{ $label }}</div>
                <h4 class="school-display text-xl font-semibold tracking-[-0.03em]">{{ $title }}</h4>
                <p class="mt-1 text-sm text-school-muted" x-text="result === '{{ $state }}' && responseMessage ? responseMessage : '{{ $description }}'">{{ $description }}</p>
            </div>
        </div>
        <span class="hidden rounded-[5px] border px-2 py-1 text-[10px] font-semibold sm:inline-flex {{ $toneClass }}">{{ $tone === 'success' ? 'TERCATAT' : ($tone === 'duplicate' ? 'SUDAH ADA' : 'DITOLAK') }}</span>
    </div>

    @if ($state === 'success')
        <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#dcefe8] pt-4 sm:grid-cols-4">
            @foreach ([['label' => 'Nama', 'value' => 'Nadia Putri'], ['label' => 'NISN', 'value' => '2403107'], ['label' => 'Kelas', 'value' => 'XI TKJ 1'], ['label' => 'Waktu', 'value' => '08:30 WIB']] as $detail)
                <div><div class="text-[10px] uppercase tracking-[0.1em] text-school-soft">{{ $detail['label'] }}</div><div class="mt-1 text-xs font-semibold">{{ $detail['value'] }}</div></div>
            @endforeach
        </div>
    @endif

    <div class="mt-5 flex items-center justify-between gap-4 border-t border-black/5 pt-4 text-xs text-school-muted">
        <span class="hidden items-center gap-2 sm:flex"><i class="ti ti-route" aria-hidden="true"></i>QR · lokasi · status · pencatatan</span>
        <button type="button" class="school-button school-button-primary ml-auto min-h-9 px-4 text-xs" @click="{{ $actionCall }}">{{ $action }}</button>
    </div>
</section>
