@extends('layouts.app')
@section('title','Buat Tugas')
@section('page_title','Buat Tugas')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <form action="{{ route('kelas.tugas.store', $kelas) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Judul</label>
            <input type="text" name="judul" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Deadline (opsional)</label>
            <input type="datetime-local" name="deadline" class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            <a href="{{ route('kelas.show', $kelas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection