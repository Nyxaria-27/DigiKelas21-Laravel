@extends('layouts.app')
@section('title', $kelas->nama_kelas)
@section('page_title', $kelas->nama_kelas)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-4 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $kelas->nama_kelas }}</h2>
                <p class="text-xs text-gray-500">Kode: {{ $kelas->kode_kelas }}</p>
            </div>
            @if(auth()->user()->role === 'Guru' && auth()->id() === $kelas->guru_id)
            <div class="flex gap-2">
                <a href="{{ route('kelas.edit', $kelas) }}" class="px-3 py-1 bg-yellow-400 text-white rounded">Edit</a>
                <form action="{{ route('kelas.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Hapus kelas?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Hapus</button>
                </form>
            </div>
            @endif
        </div>

        {{-- Materi --}}
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Materi</h3>
                @if(auth()->user()->role === 'Guru' && auth()->id() === $kelas->guru_id)
                <a href="{{ route('kelas.materi.create', $kelas) }}" class="text-sm text-indigo-600">+ Upload Materi</a>
                @endif
            </div>
            <div class="mt-3 space-y-3">
                @forelse($materi as $m)
                <div class="p-3 border rounded hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <a href="{{ route('materi.show', $m) }}" class="font-semibold">{{ $m->judul }}</a>
                        <div class="text-xs text-gray-500">{{ Str::upper($m->tipe_file ?? '—') }} • {{ $m->created_at->diffForHumans() }}</div>
                    </div>
                    @if(auth()->user()->role === 'Guru' && auth()->id() === $kelas->guru_id)
                    <form action="{{ route('materi.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus materi?')">
                        @csrf @method('DELETE')
                        <button class="px-2 py-1 bg-red-500 text-white rounded text-sm">Hapus</button>
                    </form>
                    @endif
                </div>
                @empty
                <p class="text-sm text-gray-500 mt-2">Belum ada materi.</p>
                @endforelse
            </div>
        </div>

        {{-- Tugas --}}
        <div>
            <div class="flex justify-between items-center">
                <h3 class="font-medium">Tugas</h3>
                @if(auth()->user()->role === 'Guru' && auth()->id() === $kelas->guru_id)
                <a href="{{ route('kelas.tugas.create', $kelas) }}" class="text-sm text-indigo-600">+ Buat Tugas</a>
                @endif
            </div>
            <div class="mt-3 space-y-3">
                @forelse($tugas as $t)
                <div class="p-3 border rounded hover:bg-gray-50 flex justify-between items-center">
                    <div>
                        <a href="{{ route('tugas.show', $t) }}" class="font-semibold">{{ $t->judul }}</a>
                        <div class="text-xs text-gray-500">
                            Deadline: {{ $t->deadline ? $t->deadline->format('d M Y H:i') : '—' }} • {{ $t->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 mt-2">Belum ada tugas.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar kelas --}}
    <aside class="bg-white p-4 rounded shadow">
        <h4 class="text-sm font-semibold">Detail</h4>
        <p class="text-sm text-gray-600 mt-2">Guru: {{ $kelas->guru->nama ?? $kelas->guru->name }}</p>
        <p class="text-sm text-gray-600 mt-1">Anggota: {{ $siswa->count() }} siswa</p>

        <div class="mt-4">
            <h5 class="text-xs text-gray-500">Anggota</h5>
            <ul class="mt-2 space-y-1 text-sm">
                @forelse($siswa as $s)
                <li>{{ $s->nama ?? $s->name }}</li>
                @empty
                <li class="text-xs text-gray-500">Belum ada siswa.</li>
                @endforelse
            </ul>
        </div>
    </aside>
</div>
@endsection