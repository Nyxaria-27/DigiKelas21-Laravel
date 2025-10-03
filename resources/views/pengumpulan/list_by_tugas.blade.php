@extends('layouts.app')

@section('title', 'Pengumpulan - ' . $tugas->judul)
@section('page_title', 'Pengumpulan — ' . $tugas->judul)

@section('content')
<div class="bg-white p-4 rounded shadow">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">{{ $tugas->judul }}</h2>
            <p class="text-xs text-gray-500">Kelas: {{ $tugas->kelas->nama_kelas }}</p>
        </div>
        <div class="text-sm text-gray-600">
            Deadline: {{ $tugas->deadline ? $tugas->deadline->format('d M Y H:i') : '—' }}
        </div>
    </div>

    <div class="mt-4">
        @if($pengumpulan->count())
        <div class="space-y-3">
            @foreach($pengumpulan as $p)
            <div class="p-3 border rounded flex justify-between items-center">
                <div>
                    <div class="font-medium">{{ $p->siswa->nama ?? $p->siswa->name }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $p->tanggal_kumpul ? $p->tanggal_kumpul->diffForHumans() : $p->created_at->diffForHumans() }}
                        @if($tugas->deadline && $p->tanggal_kumpul)
                        @if($p->tanggal_kumpul->greaterThan($tugas->deadline))
                        <span class="ml-2 inline-block text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">Terlambat</span>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($p->path_file_jawaban)
                    <a href="{{ asset('storage/'.$p->path_file_jawaban) }}" target="_blank" class="px-2 py-1 border rounded text-sm">Unduh</a>
                    @endif

                    <a href="{{ route('pengumpulan.show', $p) }}" class="px-2 py-1 border rounded text-sm">Lihat</a>

                    {{-- tombol nilai -> buka halaman edit nilai --}}
                    <a href="{{ route('pengumpulan.editNilai', $p) }}" class="px-2 py-1 bg-yellow-400 text-white rounded text-sm">Nilai</a>

                    {{-- tampilkan ringkasan nilai --}}
                    @if($p->nilai !== null)
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Nilai: {{ $p->nilai }}</span>
                    @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm">Belum dinilai</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $pengumpulan->links() }}
        </div>
        @else
        <p class="text-sm text-gray-500">Belum ada pengumpulan untuk tugas ini.</p>
        @endif
    </div>
</div>
@endsection