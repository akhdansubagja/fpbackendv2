{{-- File: resources/views/admin/users/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Pengguna: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    Informasi Pribadi
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">ID Pengguna</th>
                            <td>: {{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>: {{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>: {{ $user->nomor_telepon }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>: {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $user->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Rekening</th>
                            <td>: {{ $user->nomor_rekening ?? 'Belum diisi' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Registrasi</th>
                            <td>: {{ $user->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Dokumen SIM
                </div>
                <div class="card-body text-center">
                    @if($user->path_sim)
                        @php
                            $simPath = str_replace('public/', '', $user->path_sim);
                        @endphp
                        <a href="{{ url('storage/' . $simPath) }}" target="_blank">
                            <img src="{{ url('storage/' . $simPath) }}" alt="Foto SIM" class="img-fluid rounded border">
                        </a>
                    @else
                        <p class="text-muted fst-italic mt-3">Foto SIM tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection