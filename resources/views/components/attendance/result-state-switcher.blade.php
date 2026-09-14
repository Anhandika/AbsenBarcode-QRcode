<div class="flex flex-wrap gap-2" role="tablist" aria-label="Pilih hasil validasi demo">
    @foreach ([['key' => 'success', 'label' => 'Berhasil', 'icon' => 'ti-circle-check'], ['key' => 'expired', 'label' => 'QR kedaluwarsa', 'icon' => 'ti-clock-x'], ['key' => 'outside', 'label' => 'Di luar area', 'icon' => 'ti-map-pin-off'], ['key' => 'duplicate', 'label' => 'Sudah tercatat', 'icon' => 'ti-rotate-2']] as $item)
        <button
            type="button"
            role="tab"
            class="result-tab school-button school-button-secondary min-h-9 px-3 py-2 text-xs"
            :aria-selected="result === '{{ $item['key'] }}' ? 'true' : 'false'"
            @click="selectDemo('{{ $item['key'] }}')"
        >
            <i class="ti {{ $item['icon'] }}" aria-hidden="true"></i>{{ $item['label'] }}
        </button>
    @endforeach
</div>
