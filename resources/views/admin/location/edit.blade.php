@extends('layouts.app')
@push('scripts')
@if(config('services.google_maps_key'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps_key') }}&libraries=places&language=id&region=ID" async defer></script>
@endif
<style>
#schoolMap { height: 360px; border-radius: 12px; }
.map-marker { background: #2c68f5; border: 3px solid white; border-radius: 50%; width: 24px; height: 24px; cursor: move; box-shadow: 0 2px 8px rgba(0,0,0,.3); }
.leaflet-container { height: 360px; border-radius: 12px; }
</style>
@endpush
@section('content')
<div class="max-w-[1000px] mx-auto">
    <div class="mb-6 animate-[fadeIn_.6s_ease]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-smk.png') }}" alt="Logo" class="h-10 w-10 rounded-xl bg-white p-1.5 shadow-sm border border-school-line">
                <div>
                    <h1 class="school-display text-xl font-bold text-[#0f1e3d]">Lokasi Sekolah</h1>
                    <p class="text-xs text-[#68748b]">Atur koordinat & radius absensi SMK Bina Utama</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('ok'))
    <div class="mb-4 animate-[slideIn_.4s_ease] rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-center justify-between">
        {{ session('ok') }}
        <button @click="$el.parentElement.remove()" class="text-green-500 hover:text-green-700"><i class="ti ti-x"></i></button>
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_420px]">
        {{-- Form --}}
        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm p-6 sm:p-8 animate-[fadeInUp_.5s_ease]">
            <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-gradient-to-br from-[#2c68f5]/10 to-[#8ea8ff]/10 blur"></div>
            <form method="POST" action="{{ route('admin.location.update') }}" class="space-y-5" id="locationForm">@csrf @method('PUT')
                <div>
                    <label class="text-sm font-semibold text-[#172033] block mb-1.5">Nama Sekolah</label>
                    <input name="name" value="{{ old('name',$school->name) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10">
                </div>
                <div>
                    <label class="text-sm font-semibold text-[#172033] block mb-1.5">Alamat</label>
                    <input name="address" value="{{ old('address',$school->address) }}" class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="Jl. Contoh No. 123, Kota">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-[#172033] block mb-1.5">Latitude</label>
                        <input name="latitude" id="latInput" type="number" step="0.0000001" value="{{ old('latitude',$school->latitude) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="-6.9182000">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-[#172033] block mb-1.5">Longitude</label>
                        <input name="longitude" id="lngInput" type="number" step="0.0000001" value="{{ old('longitude',$school->longitude) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="110.2056000">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-[#172033] block mb-1.5">Radius Absensi (meter)</label>
                    <input name="radius_meters" id="radiusInput" type="number" min="10" max="5000" value="{{ old('radius_meters',$school->radius_meters) }}" required class="w-full h-11 rounded-xl border border-[#e3e8f0] bg-white px-3.5 text-sm focus:border-[#2c68f5] focus:ring-[3px] focus:ring-[#2c68f5]/10" placeholder="80">
                    <p class="mt-1 text-xs text-[#8a95a8]">Jarak maksimal dari titik sekolah untuk absensi valid (10-5000m)</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 h-11 rounded-xl bg-gradient-to-r from-[#1a3a7a] to-[#2c68f5] text-white font-bold text-sm shadow-[0_8px_20px_rgba(44,104,245,.35)] hover:shadow-[0_10px_28px_rgba(44,104,245,.45)] active:translate-y-[1px] transition flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy"></i> Simpan Lokasi
                    </button>
                    <button type="button" id="geocodeBtn" class="px-5 h-11 rounded-xl border border-school-line bg-white text-sm font-semibold text-[#172033] hover:bg-school-canvas transition flex items-center justify-center gap-2">
                        <i class="ti ti-map-pin"></i> Cari Alamat
                    </button>
                </div>
            </form>
        </div>

        {{-- Map Preview --}}
        <div class="relative overflow-hidden rounded-2xl bg-white border border-school-line shadow-sm animate-[fadeInUp_.5s_ease_.1s]">
            <div class="absolute -top-4 -right-4 h-24 w-24 rounded-full bg-gradient-to-br from-[#167a67]/10 to-[#2cf0b8]/10 blur"></div>
            <div class="p-4 sm:p-5 border-b border-school-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#623ed8]">Peta Lokasi</p>
                        <h3 class="school-display text-lg font-bold text-[#0f1e3d]">Pratinjau Radius</h3>
                    </div>
                    <select id="mapStyle" class="text-sm rounded-xl border border-school-line px-3 py-1.5 bg-white">
                        <option value="roadmap">Jalan</option>
                        <option value="satellite">Satelit</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="terrain">Terrain</option>
                    </select>
                </div>
            </div>
            <div class="p-4 sm:p-5">
                <div id="schoolMap" class="rounded-xl overflow-hidden"></div>
                <div class="mt-3 flex flex-wrap gap-2 text-xs text-[#8a95a8]">
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#2c68f5]"></span> Titik Sekolah</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full border-2 border-[#167a67] bg-transparent"></span> Radius {{ $school->radius_meters }}m</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const latInput = document.getElementById('latInput');
    const lngInput = document.getElementById('lngInput');
    const radiusInput = document.getElementById('radiusInput');
    const geocodeBtn = document.getElementById('geocodeBtn');
    const mapStyle = document.getElementById('mapStyle');
    const mapContainer = document.getElementById('schoolMap');

    let map, marker, circle;

    function initMap() {
        if (!window.google || !window.google.maps) {
            console.warn('Google Maps not loaded');
            mapContainer.innerHTML = '<div class="h-full flex items-center justify-center text-[#8a95a8]"><i class="ti ti-map-off text-3xl"></i><p class="ml-2">Google Maps API key tidak dikonfigurasi</p></div>';
            return;
        }
        const lat = parseFloat(latInput.value) || -6.9182;
        const lng = parseFloat(lngInput.value) || 110.2056;
        const radius = parseInt(radiusInput.value) || 80;

        map = new google.maps.Map(mapContainer, {
            center: { lat, lng },
            zoom: 16,
            mapTypeId: mapStyle.value,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            styles: [{ featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }]
        });

        marker = new google.maps.Marker({
            position: { lat, lng },
            map,
            draggable: true,
            title: 'Titik Sekolah (drag untuk pindah)',
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="10" fill="#2c68f5"/><circle cx="16" cy="16" r="6" fill="white"/></svg>'),
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 16)
            }
        });

        circle = new google.maps.Circle({
            strokeColor: '#167a67',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#167a67',
            fillOpacity: 0.12,
            map,
            center: { lat, lng },
            radius: radius,
            clickable: false
        });

        marker.addListener('dragend', (e) => {
            latInput.value = e.latLng.lat().toFixed(7);
            lngInput.value = e.latLng.lng().toFixed(7);
            circle.setCenter(e.latLng);
        });

        radiusInput.addEventListener('input', () => {
            const r = parseInt(radiusInput.value) || 0;
            circle.setRadius(Math.max(10, Math.min(5000, r)));
        });

        mapStyle.addEventListener('change', () => {
            map.setMapTypeId(mapStyle.value);
        });
    }

    geocodeBtn.addEventListener('click', () => {
        if (!window.google || !window.google.maps) return alert('Google Maps belum dimuat');
        const address = document.querySelector('input[name="address"]').value;
        if (!address) return alert('Isi alamat terlebih dahulu');
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address, region: 'ID' }, (results, status) => {
            if (status === 'OK' && results[0]) {
                const loc = results[0].geometry.location;
                latInput.value = loc.lat().toFixed(7);
                lngInput.value = loc.lng().toFixed(7);
                if (map && marker) {
                    map.setCenter(loc);
                    marker.setPosition(loc);
                    circle.setCenter(loc);
                } else {
                    initMap();
                }
            } else {
                alert('Alamat tidak ditemukan: ' + status);
            }
        });
    });

    // wait for Google Maps to load
    if (window.google && window.google.maps) {
        initMap();
    } else {
        window.initSchoolMap = initMap;
    }
});
</script>
@endpush