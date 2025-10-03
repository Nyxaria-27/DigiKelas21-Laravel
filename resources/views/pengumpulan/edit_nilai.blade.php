@extends('layouts.app')
@section('title','Nilai Pengumpulan')
@section('page_title','Nilai Pengumpulan')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <h3 class="font-semibold">Nilai untuk: {{ $pengumpulan->siswa->nama ?? $pengumpulan->siswa->name }}</h3>
    <p class="text-xs text-gray-500">Tugas: {{ $pengumpulan->tugas->judul }}</p>

    <form action="{{ route('pengumpulan.updateNilai', $pengumpulan) }}" method="POST" class="mt-4">
        @csrf @method('PATCH')

        <div class="mb-3">
            <label class="block text-sm font-medium">Nilai (0 - 100)</label>
            <input type="number" name="nilai" min="0" max="100" value="{{ old('nilai', $pengumpulan->nilai) }}" class="mt-1 block w-32 border rounded px-3 py-2" required>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Komentar (opsional)</label>
            <textarea name="komentar" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('komentar', $pengumpulan->komentar) }}</textarea>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan Nilai</button>
            <a href="{{ route('tugas.show', $pengumpulan->tugas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection