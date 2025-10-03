@extends('layouts.app')
@section('title','Kumpulkan Tugas')
@section('page_title','Kumpulkan Tugas')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <h3 class="font-medium">{{ $tugas->judul }}</h3>
    <p class="text-xs text-gray-500">Deadline: {{ $tugas->deadline ? $tugas->deadline->format('d M Y H:i') : '—' }}</p>

    <form action="{{ route('pengumpulan.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
        <div class="mb-3">
            <label class="block text-sm font-medium">Teks Jawaban (opsional)</label>
            <textarea name="teks_jawaban" rows="6" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Upload File (opsional)</label>
            <input type="file" name="file" class="mt-1 block w-full">
            <p class="text-xs text-gray-400">PDF/DOC/ZIP — Maks 15MB</p>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Kirim</button>
            <a href="{{ route('tugas.show', $tugas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection