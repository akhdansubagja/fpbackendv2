@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Detail Pengguna: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 fw-bold">Informasi Pribadi</h6>
                </div>
                <div class="card-body">
                    {{-- Menggunakan info-grid yang stylenya kita tambah di admin.blade.php --}}
                    <div class="info-grid">
                        <strong>ID Pengguna:</strong> <span>{{ $user->id }}</span>
                        <strong>Nama Lengkap:</strong> <span>{{ $user->name }}</span>
                        <strong>Email:</strong> <span>{{ $user->email }}</span>
                        <strong>Nomor Telepon:</strong> <span>{{ $user->nomor_telepon }}</span>
                        <strong>Tanggal Lahir:</strong> <span>{{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</span>
                        <strong>Alamat:</strong> <span>{{ $user->alamat }}</span>
                        <strong>Nomor Rekening:</strong> <span>{{ $user->nomor_rekening ?? 'Belum diisi' }}</span>
                        <strong>Tgl. Registrasi:</strong> <span>{{ $user->created_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 fw-bold">Dokumen SIM</h6>
                </div>
                <div class="card-body text-center">
                    @if($user->path_sim)
                        @php
                            $simPath = str_replace('public/', '', $user->path_sim);
                        @endphp
                        <div class="image-container">
                             <a href="{{ url('storage/' . $simPath) }}" target="_blank">
                                <img src="{{ url('storage/' . $simPath) }}" alt="Foto SIM">
                            </a>
                        </div>
                    @else
                        <p class="text-muted-color fst-italic mt-3">Foto SIM tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection