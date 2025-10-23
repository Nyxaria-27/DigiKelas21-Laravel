@extends('layouts.app')

@section('title','Dashboard Guru')
@section('page_title','Dashboard — Guru')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-medium text-gray-700">Kelas yang dibuat</h3>
        <div class="mt-3 space-y-2">
            @forelse($kelas as $k)
            <a href="{{ route('kelas.show',$k) }}" class="block p-2 border rounded hover:bg-indigo-50">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-semibold">{{ $k->nama_kelas }}</div>   
                    </div>
                    <div class="text-sm text-gray-600">{{ $k->siswa_count ?? 0 }} siswa</div>
                </div>
            </a>
            @empty
            <p class="text-sm text-gray-500">Belum ada kelas. <a href="{{ route('kelas.create') }}" class="text-indigo-600">Buat kelas</a></p>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-medium text-gray-700">Tugas terbaru</h3>
        <ul class="mt-3 space-y-2">
            @forelse($recentTugas as $t)
            <li class="p-2 border rounded hover:bg-indigo-50">
                <a href="{{ route('tugas.show', $t) }}" class="block">
                    <div class="font-semibold">{{ $t->judul }}</div>
                    <div class="text-xs text-gray-500">{{ $t->kelas->nama_kelas }} — {{ $t->created_at->diffForHumans() }}</div>
                </a>
            </li>
            @empty
            <li class="text-sm text-gray-500">Tidak ada tugas terbaru.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection