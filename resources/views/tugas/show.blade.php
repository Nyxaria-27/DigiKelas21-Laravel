@extends('layouts.app')
@section('title', $tugas->judul)
@section('page_title', $tugas->judul)

@section('content')
<div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold">{{ $tugas->judul }}</h2>
    <div class="text-xs text-gray-500">{{ $tugas->kelas->nama_kelas }} • {{ $tugas->created_at->diffForHumans() }}</div>
    <div class="mt-3 text-sm text-gray-700">{{ $tugas->deskripsi }}</div>
    <div class="mt-3 text-sm text-gray-600">Deadline: {{ $tugas->deadline ? $tugas->deadline->format('d M Y H:i') : '—' }}</div>

    @if(auth()->user()->role === 'Siswa')

    @if($pengumpulanSaya)
    {{-- Jika sudah kumpul --}}
    <div class="mt-4 p-3 border rounded bg-green-50">
        <p class="text-sm text-gray-700">
            ✅ Kamu sudah mengumpulkan tugas ini
            ({{ $pengumpulanSaya->tanggal_kumpul ? $pengumpulanSaya->tanggal_kumpul->diffForHumans() : $pengumpulanSaya->created_at->diffForHumans() }})
        </p>
        <div class="mt-2">
            <a href="{{ route('pengumpulan.show', $pengumpulanSaya->id) }}"
                class="px-3 py-2 bg-indigo-600 text-white rounded">
                Lihat Pengumpulan
            </a>
        </div>
    </div>
    @else
    <div class="mt-4">
        <a href="{{ route('pengumpulan.create', ['tugas_id'=>$tugas->id]) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Kumpulkan Tugas</a>
    </div> 
    @endif
    
    @endif

    @if(auth()->user()->role === 'Guru' && isset($pengumpulan))
    <div class="mt-6">
        <h3 class="font-medium">Pengumpulan Siswa</h3>
        @if(auth()->user()->role === 'Guru')
        <div class="mt-4">
            <a href="{{ route('tugas.pengumpulan.index', $tugas) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Lihat Semua Pengumpulan</a>
        </div>
        @endif

    </div>
    @endif
</div>
@endsection