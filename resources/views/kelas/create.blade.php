@extends('layouts.app')
@section('title','Buat Kelas')
@section('page_title','Buat Kelas')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <form action="{{ route('kelas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
            <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Buat</button>
            <a href="{{ route('kelas.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection