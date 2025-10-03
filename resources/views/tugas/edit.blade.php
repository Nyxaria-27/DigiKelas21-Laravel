@extends('layouts.app')
@section('title','Edit Tugas')
@section('page_title','Edit Tugas')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <form action="{{ route('tugas.update', $tugas) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="block text-sm font-medium">Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="mt-1 block w-full border rounded px-3 py-2">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Deadline</label>
            <input type="datetime-local" name="deadline" value="{{ $tugas->deadline ? $tugas->deadline->format('Y-m-d\TH:i') : '' }}" class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            <a href="{{ route('tugas.show', $tugas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection