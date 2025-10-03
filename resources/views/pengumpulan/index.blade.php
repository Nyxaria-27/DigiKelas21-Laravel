@extends('layouts.app')
@section('title','Pengumpulan Saya')
@section('page_title','Pengumpulan Saya')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <h3 class="font-medium">Daftar Pengumpulan</h3>
    <div class="mt-3 space-y-3">
        @forelse($pengumpulan as $p)
        <div class="p-3 border rounded flex justify-between items-center">
            <div>
                <div class="font-semibold">{{ $p->tugas->judul }}</div>
                <div class="text-xs text-gray-500">Kelas: {{ $p->tugas->kelas->nama_kelas }} • {{ $p->tanggal_kumpul ? $p->tanggal_kumpul->diffForHumans() : $p->created_at->diffForHumans() }}</div>
            </div>
            <div class="text-sm">
                @if($p->nilai !== null)
                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Nilai: {{ $p->nilai }}</span>
                @else
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Belum dinilai</span>
                @endif
                <a href="{{ route('pengumpulan.show', $p) }}" class="ml-2 px-2 py-1 border rounded">Lihat</a>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500">Belum ada pengumpulan.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $pengumpulan->links() }}
    </div>
</div>
@endsection