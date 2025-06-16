<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemesanan</title>
    {{-- Menggunakan style yang sama dengan halaman vehicles --}}
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .btn {
            background-color: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Pemesanan</h1>
        </div>

        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Waktu Sewa</th>
                    <th>Waktu Kembali</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rentals as $rental)
                    <tr>
                        <td>{{ $rental->id }}</td>
                        <td>{{ $rental->user->name }}</td>
                        <td>{{ $rental->vehicle->merk }} {{ $rental->vehicle->nama }}</td>
                        <td>{{ $rental->waktu_sewa }}</td>
                        <td>{{ $rental->waktu_kembali }}</td>
                        <td>Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $rental->status_pemesanan }}</td>
                        {{-- Ganti seluruh isi <td> untuk kolom "Aksi" --}}
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <a href="{{ route('admin.rentals.show', $rental->id) }}"
                                    style="text-decoration: none; padding: 0.4rem; background-color: #17a2b8; color: white; text-align: center; border-radius: 4px;">Lihat
                                    Detail</a>

                                @if($rental->status_pemesanan == 'pending')
                                    <div style="display: flex; gap: 0.5rem;">
                                        {{-- Form untuk Konfirmasi --}}
                                        <form action="{{ route('admin.rentals.update-status', $rental->id) }}" method="POST"
                                            style="flex: 1;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pemesanan" value="dikonfirmasi">
                                            <button type="submit" class="btn">Konfirmasi</button>
                                        </form>
                                        {{-- Form untuk Tolak --}}
                                        <form action="{{ route('admin.rentals.update-status', $rental->id) }}" method="POST"
                                            style="flex: 1;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pemesanan" value="ditolak">
                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data pemesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>