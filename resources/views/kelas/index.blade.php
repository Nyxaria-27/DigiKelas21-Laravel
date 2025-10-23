@extends('layouts.app')
@section('title','Daftar Kelas')
@section('page_title','Daftar Kelas')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($kelas as $k)
    <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-start">
            <div>
                <a href="{{ route('kelas.show',$k) }}" class="text-lg font-semibold text-indigo-600">{{ $k->nama_kelas }}</a>
                <div class="text-xs text-gray-500">Kode Guru: {{ $k->kode_guru }}</div>
                <div class="text-xs text-gray-500">Kode Siswa: {{ $k->kode_siswa }}</div>
            </div>
            <div class="text-sm text-gray-600">{{ $k->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $kelas->links() }}
</div>
@endsection