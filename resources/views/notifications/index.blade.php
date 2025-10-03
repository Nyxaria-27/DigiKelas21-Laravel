@extends('layouts.app')

@section('title','Notifikasi')
@section('page_title','Notifikasi Saya')

@section('content')
<div class="bg-white p-4 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Notifikasi</h2>
        <div class="flex items-center gap-2">
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button class="px-3 py-1 text-sm bg-indigo-600 text-white rounded">Tandai semua terbaca</button>
            </form>
        </div>
    </div>

    @if($notifications->count())
    <div class="space-y-2">
        @foreach($notifications as $note)
        <div class="p-3 border rounded {{ $note->read_at ? 'bg-gray-50' : 'bg-white' }}">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-sm font-medium">{{ $note->data['message'] ?? ($note->data['judul'] ?? 'Notifikasi') }}</div>
                    <div class="text-xs text-gray-500 mt-1"> {{ $note->time ?? $note->created_at->diffForHumans() }}</div>
                </div>
                <div class="text-right">
                    @if(!$note->read_at)
                    <form action="{{ route('notifications.read', $note->id) }}" method="POST">
                        @csrf
                        <button class="text-xs text-indigo-600">Tandai terbaca</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
    @else
    <p class="text-sm text-gray-500">Belum ada notifikasi.</p>
    @endif
</div>
@endsection