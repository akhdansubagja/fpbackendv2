<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kendaraan</title>
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
            background-color: #007bff;
            color: white;
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            border-radius: 5px;
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

        img {
            max-width: 100px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Kendaraan</h1>
            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('admin.vehicles.create') }}" class="btn">Tambah Kendaraan Baru</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto Utama</th>
                    <th>Merk</th>
                    <th>Nama</th>
                    <th>Harga/Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->id }}</td>
                        <td><img src="{{ $vehicle->foto_utama }}" alt="{{ $vehicle->nama }}"></td>
                        <td>{{ $vehicle->merk }}</td>
                        <td>{{ $vehicle->nama }}</td>
                        <td>Rp {{ number_format($vehicle->harga_sewa_harian, 0, ',', '.') }}</td>
                        <td>{{ $vehicle->status }}</td>
                        {{-- PERBAIKAN --}}
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}"
                                style="text-decoration: none;">Edit</a>
                            <span>|</span>
                            <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background: none; border: none; color: #007bff; cursor: pointer; padding: 0; font-size: inherit;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data kendaraan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>