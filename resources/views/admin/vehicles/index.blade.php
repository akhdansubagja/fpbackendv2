@extends('layouts.admin')

@section('title', 'Manajemen Kendaraan')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Manajemen Kendaraan</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kendaraan Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Merk & Nama</th>
                            <th scope="col">Harga/Hari</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <img src="{{ $vehicle->foto_utama }}" alt="{{ $vehicle->nama }}" class="img-fluid rounded" style="max-width: 100px;">
                                </td>
                                <td>
                                    <strong>{{ $vehicle->merk }}</strong><br>
                                    <span class="text-muted-color">{{ $vehicle->nama }}</span>
                                </td>
                                <td>Rp {{ number_format($vehicle->harga_sewa_harian, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('admin.vehicles.updateStatus', $vehicle->id) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="tersedia" {{ $vehicle->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="servis" {{ $vehicle->status == 'servis' ? 'selected' : '' }}>Servis</option>
                                            <option value="disewa" {{ $vehicle->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-light">Go</button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data kendaraan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection