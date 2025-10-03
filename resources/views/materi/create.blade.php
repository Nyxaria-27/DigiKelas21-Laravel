@extends('layouts.app')
@section('title','Upload Materi')
@section('page_title','Upload Materi')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <form action="{{ route('kelas.materi.store', $kelas) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Judul</label>
            <input type="text" name="judul" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Deskripsi (opsional)</label>
            <textarea name="deskripsi" rows="4" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">File (PDF/PPT/DOC/ZIP)</label>
            <input type="file" name="file" class="mt-1 block w-full">
            <p class="text-xs text-gray-400 mt-1">Maks 15MB.</p>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Upload</button>
            <a href="{{ route('kelas.show', $kelas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection