@extends('layouts.app')
@section('content')
<div class="max-w-[1200px] mx-auto">
<h1 class="school-display text-xl sm:text-2xl font-bold mb-4">Laporan Kehadiran</h1>
<form class="flex flex-wrap gap-2 mb-4"><input type="date" name="date" value="{{ $date }}" class="rounded-xl border border-school-line px-3 py-2 text-sm"><button class="school-button school-button-secondary">Filter</button><a href="{{ route('admin.reports.export',['date'=>$date]) }}" class="school-button school-button-primary"><i class="ti ti-download"></i>Export CSV</a></form>
<div class="school-panel overflow-x-auto"><table class="w-full text-sm"><thead class="bg-school-canvas text-xs"><tr><th class="px-4 py-3 text-left">Nama</th><th class="px-4 py-3">Kelas</th><th class="px-4 py-3">Jam</th><th class="px-4 py-3">Hasil</th></tr></thead><tbody>@foreach($attendances as $a)<tr class="border-t"><td class="px-4 py-3 font-semibold">{{ $a->user->name }}<div class="text-xs text-school-muted">{{ $a->user->identifier }}</div></td><td class="px-4 py-3">{{ $a->user->class_name }}</td><td class="px-4 py-3">{{ $a->scanned_at?->format('H:i') }}</td><td class="px-4 py-3"><span class="rounded-full px-2 py-1 text-xs font-bold {{ $a->result->value==='success'?'bg-green-100 text-green-700':'bg-red-100 text-red-700' }}">{{ $a->result->value }}</span></td></tr>@endforeach</tbody></table><div class="p-4">{{ $attendances->links() }}</div></div>
</div>
@endsection
