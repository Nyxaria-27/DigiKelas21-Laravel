@extends('layouts.app')
@section('title','Edit Kelas')
@section('page_title','Edit Kelas')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="block text-sm font-medium">Nama Kelas</label>
            <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            <a href="{{ route('kelas.show', $kelas) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection