@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Notifikasi</h1>
</div>

<div class="card">
    <div class="card-header">
        Daftar Notifikasi
    </div>
    <div class="list-group list-group-flush">
        @forelse ($notifications as $notification)
            {{-- Setiap notifikasi sekarang adalah sebuah link yang mengarah ke route 'read' --}}
            <a href="{{ route('admin.notifications.read', $notification) }}" 
               class="list-group-item list-group-item-action 
                      {{ !$notification->is_read ? 'list-group-item-light fw-bold' : '' }}">
                
                <div class="d-flex w-100 justify-content-between">
                    <p class="mb-1">
                        {{-- Jika belum dibaca, tampilkan titik biru --}}
                        @if(!$notification->is_read)
                            <span class="badge bg-primary rounded-pill">&nbsp;</span>
                        @endif
                        {{ $notification->title }}
                    </p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1 ms-4">{{ $notification->message }}</p>
            </a>
        @empty
            <div class="list-group-item">
                <p class="mb-0 text-center text-muted">Tidak ada notifikasi.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection