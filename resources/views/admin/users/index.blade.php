@extends('layouts.app')
@section('content')
<div class="max-w-[1200px] mx-auto">
<div class="flex flex-wrap gap-3 justify-between items-center mb-6">
<h1 class="school-display text-xl sm:text-2xl font-bold capitalize">Data {{ $role }}</h1>
<a href="{{ route('admin.users.create',$role) }}" class="school-button school-button-primary"><i class="ti ti-plus"></i>Tambah</a>
</div>
<form class="mb-4 flex gap-2"><input name="q" value="{{ $q }}" placeholder="Cari nama/NISN/email" class="flex-1 rounded-xl border border-school-line px-3 py-2 text-sm"><button class="school-button school-button-secondary">Cari</button></form>
@if(session('ok'))<div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-2 text-sm text-green-700">{{ session('ok') }}</div>@endif
<div class="school-panel overflow-hidden">
<div class="hidden sm:block overflow-x-auto"><table class="w-full text-sm"><thead class="bg-school-canvas text-xs"><tr><th class="px-4 py-3 text-left">Nama</th><th class="px-4 py-3 text-left">NISN/ID</th><th class="px-4 py-3 text-left">Email</th><th class="px-4 py-3 text-left">Kelas</th><th class="px-4 py-3"></th></tr></thead><tbody>@foreach($users as $u)<tr class="border-t"><td class="px-4 py-3 font-semibold">{{ $u->name }}</td><td class="px-4 py-3">{{ $u->identifier }}</td><td class="px-4 py-3">{{ $u->email }}</td><td class="px-4 py-3">{{ $u->class_name ?? '-' }}</td><td class="px-4 py-3 flex gap-2"><a href="{{ route('admin.users.edit',[$role,$u]) }}" class="text-school-blue">Edit</a><form method="POST" action="{{ route('admin.users.destroy',[$role,$u]) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td></tr>@endforeach</tbody></table></div>
<div class="sm:hidden divide-y">@foreach($users as $u)<div class="p-4"><div class="font-bold">{{ $u->name }}</div><div class="text-xs text-school-muted">{{ $u->identifier }} • {{ $u->class_name }}</div><div class="flex gap-3 mt-2"><a href="{{ route('admin.users.edit',[$role,$u]) }}" class="text-sm text-school-blue font-semibold">Edit</a><form method="POST" action="{{ route('admin.users.destroy',[$role,$u]) }}">@csrf @method('DELETE')<button class="text-sm text-red-600 font-semibold">Hapus</button></form></div></div>@endforeach</div>
<div class="p-4">{{ $users->links() }}</div>
</div></div>
@endsection
