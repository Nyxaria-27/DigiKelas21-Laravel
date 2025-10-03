@extends('layouts.app')
@section('title', $materi->judul)
@section('page_title', $materi->judul)

@section('content')
<div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold">{{ $materi->judul }}</h2>
    <p class="text-sm text-gray-500">{{ $materi->created_at->diffForHumans() }}</p>
    @if($materi->deskripsi)
    <div class="mt-3 text-sm text-gray-700">{{ $materi->deskripsi }}</div>
    @endif

    @if($materi->path_file)
    <div class="mt-4">
        <a href="{{ asset('storage/'.$materi->path_file) }}" target="_blank" class="inline-block px-3 py-2 border rounded hover:bg-indigo-50">Unduh / Buka File</a>
    </div>
    @endif
</div>
@endsection