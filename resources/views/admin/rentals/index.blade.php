@extends('layouts.admin')

@section('title', 'Manajemen Pemesanan')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Manajemen Pemesanan</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                {{-- Saya menghapus kelas table-striped, hover, bordered karena sudah diatur di CSS utama --}}
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Penyewa</th>
                            <th scope="col">Kendaraan</th>
                            <th scope="col">Tanggal Sewa</th>
                            <th scope="col">Status Pemesanan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rentals as $rental)
                        <tr>
                            <th scope="row">#GR{{ $rental->id }}</th>
                            <td>{{ $rental->user->name }}</td>
                            <td>{{ $rental->vehicle->merk }} {{ $rental->vehicle->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($rental->waktu_sewa)->format('d M Y, H:i') }}</td>
                            <td>
                                @php
                                    $statusClass = '';
                                    switch ($rental->status_pemesanan) {
                                        case 'pending': $statusClass = 'bg-warning text-dark'; break;
                                        case 'dikonfirmasi': $statusClass = 'bg-info text-dark'; break;
                                        case 'berjalan': $statusClass = 'bg-success'; break;
                                        case 'selesai': $statusClass = 'bg-primary'; break;
                                        case 'dibatalkan': case 'ditolak': $statusClass = 'bg-danger'; break;
                                        default: $statusClass = 'bg-secondary';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $rental->status_pemesanan }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('admin.rentals.show', $rental->id) }}" class="btn btn-sm btn-light" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.rentals.update-status', $rental->id) }}" method="POST" class="d-inline-flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_pemesanan" class="form-select form-select-sm" style="width: 120px;">
                                            <option value="pending" {{ $rental->status_pemesanan == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="dikonfirmasi" {{ $rental->status_pemesanan == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                            <option value="berjalan" {{ $rental->status_pemesanan == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                            <option value="selesai" {{ $rental->status_pemesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditolak" {{ $rental->status_pemesanan == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="dibatalkan" {{ $rental->status_pemesanan == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-light">Go</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pemesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection