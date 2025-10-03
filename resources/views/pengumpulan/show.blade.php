@extends('layouts.app')
@section('title','Detail Pengumpulan')
@section('page_title','Detail Pengumpulan')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-lg font-semibold">{{ $pengumpulan->tugas->judul }}</h3>
            <div class="text-xs text-gray-500">Siswa: {{ $pengumpulan->siswa->nama ?? $pengumpulan->siswa->name }}</div>
        </div>
        <div class="text-sm">
            @if($pengumpulan->nilai !== null)
            <div class="text-sm font-medium text-green-600">Nilai: {{ $pengumpulan->nilai }}</div>
            @else
            <div class="text-sm font-medium text-yellow-600">Belum dinilai</div>
            @endif
        </div>
    </div>

    <div class="mt-4 text-sm text-gray-700">
        @if($pengumpulan->teks_jawaban)
        <div class="mb-3">
            <h4 class="font-medium">Jawaban Teks</h4>
            <div class="mt-1 whitespace-pre-wrap">{{ $pengumpulan->teks_jawaban }}</div>
        </div>
        @endif

        @if($pengumpulan->path_file_jawaban)
        <div>
            <h4 class="font-medium">File Jawaban</h4>
            <a href="{{ asset('storage/'.$pengumpulan->path_file_jawaban) }}" target="_blank" class="inline-block px-3 py-2 border rounded mt-1">Unduh / Buka</a>
        </div>
        @endif

        @if($pengumpulan->komentar)
        <div class="mt-3">
            <h4 class="font-medium">Komentar Guru</h4>
            <div class="mt-1 text-sm text-gray-600">{{ $pengumpulan->komentar }}</div>
        </div>
        @endif
    </div>

    {{-- Hanya tampil untuk siswa yang punya pengumpulan sendiri --}}
    @if(auth()->user()->role === 'Siswa' && auth()->id() === $pengumpulan->siswa_id && $pengumpulan->nilai === null)
    <div class="mt-6">
        <h4 class="font-medium mb-2">Update Jawaban</h4>
        <form action="{{ route('pengumpulan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="tugas_id" value="{{ $pengumpulan->tugas_id }}">

            <div>
                <label class="block text-sm font-medium">Jawaban Teks</label>
                <textarea name="teks_jawaban" rows="4" class="w-full border rounded p-2">{{ old('teks_jawaban', $pengumpulan->teks_jawaban) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium">Upload File (opsional)</label>
                <input type="file" name="file" class="block w-full text-sm border rounded p-2">
            </div>

            <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Update Pengumpulan</button>
        </form>
    </div>
    @endif

    <div class="mt-4">
        @if(auth()->user()->role === 'Siswa' && auth()->id() === $pengumpulan->siswa_id && $pengumpulan->nilai === null)
        <form action="{{ route('pengumpulan.destroy', $pengumpulan) }}" method="POST" onsubmit="return confirm('Hapus pengumpulan?')">
            @csrf 
            @method('DELETE')
            <button class="px-3 py-2 bg-red-500 text-white rounded">Hapus</button>
        </form>
        @endif
    </div>
</div>
@endsection