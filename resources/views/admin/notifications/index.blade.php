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
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $notification->title }}</h5>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ $notification->message }}</p>
            </div>
        @empty
            <div class="list-group-item">
                <p class="mb-0 text-center text-muted">Tidak ada notifikasi baru.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection