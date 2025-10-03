@extends('layouts.app')

@section('title','Dashboard Siswa')
@section('page_title','Dashboard — Siswa')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-medium text-gray-700">Kelas yang diikuti</h3>
        <div class="mt-3 space-y-2">
            @forelse($kelas as $k)
            <a href="{{ route('kelas.show', $k) }}" class="block p-2 border rounded hover:bg-indigo-50">
                <div class="font-semibold">{{ $k->nama_kelas }}</div>
                <div class="text-xs text-gray-500">Guru: {{ $k->guru->nama ?? $k->guru->name }}</div>
            </a>
            @empty
            <p class="text-sm text-gray-500">Belum mengikuti kelas. Masukkan kode kelas di sidebar untuk bergabung.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-medium text-gray-700">Tugas mendekati deadline</h3>
        <ul class="mt-3 space-y-2">
            @forelse($tugasDeadline as $t)
            <li class="p-2 border rounded hover:bg-red-50">
                <a href="{{ route('tugas.show', $t) }}" class="block">
                    <div class="font-semibold">{{ $t->judul }}</div>
                    <div class="text-xs text-gray-500">Deadline: {{ $t->deadline ? $t->deadline->format('d M Y H:i') : '—' }}</div>
                </a>
            </li>
            @empty
            <li class="text-sm text-gray-500">Tidak ada tugas mendekati deadline.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection