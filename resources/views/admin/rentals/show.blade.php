<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan #{{ $rental->id }}</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 900px; margin: auto; background: white; padding: 2rem; border-radius: 8px; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .detail-section { padding: 1.5rem; border: 1px solid #eee; border-radius: 5px; }
        h1, h3 { border-bottom: 2px solid #eee; padding-bottom: 0.5rem; margin-top: 0; }
        p { margin: 0.5rem 0; }
        strong { display: inline-block; width: 150px; }
        .proof-image { max-width: 100%; margin-top: 1rem; border-radius: 5px; border: 1px solid #ccc; }
        .back-link { display: inline-block; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Pemesanan #{{ $rental->id }}</h1>
        <div class="grid-container">
            <div class="detail-section">
                <h3>Data Penyewa</h3>
                <p><strong>Nama:</strong> {{ $rental->user->name }}</p>
                <p><strong>Email:</strong> {{ $rental->user->email }}</p>
                <p><strong>No. Telepon:</strong> {{ $rental->user->nomor_telepon }}</p>
                <p><strong>Alamat:</strong> {{ $rental->user->alamat }}</p>
            </div>

            <div class="detail-section">
                <h3>Data Kendaraan</h3>
                <p><strong>Merk:</strong> {{ $rental->vehicle->merk }}</p>
                <p><strong>Nama:</strong> {{ $rental->vehicle->nama }}</p>
                <p><strong>Harga/Hari:</strong> Rp {{ number_format($rental->vehicle->harga_sewa_harian, 0, ',', '.') }}</p>
                <img src="{{ $rental->vehicle->foto_utama }}" alt="Foto Utama" style="max-width: 100%;">
            </div>

            <div class="detail-section">
                <h3>Detail Pemesanan</h3>
                <p><strong>Waktu Sewa:</strong> {{ $rental->waktu_sewa }}</p>
                <p><strong>Waktu Kembali:</strong> {{ $rental->waktu_kembali }}</p>
                <p><strong>Total Harga:</strong> Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</p>
                <p><strong>Status Pesanan:</strong> {{ $rental->status_pemesanan }}</p>
            </div>

            <div class="detail-section">
                <h3>Detail Pembayaran</h3>
                <p><strong>Status Pembayaran:</strong> {{ $rental->payment->status_pembayaran }}</p>
                <p><strong>Bukti Bayar:</strong></p>
                @if($rental->payment->bukti_pembayaran)
                    <a href="{{ $rental->payment->bukti_pembayaran }}" target="_blank">
                        <img src="{{ $rental->payment->bukti_pembayaran }}" alt="Bukti Pembayaran" class="proof-image">
                    </a>
                @else
                    <p><i>Belum ada bukti pembayaran.</i></p>
                @endif
            </div>
        </div>

        <a href="{{ route('admin.rentals.index') }}" class="back-link">Kembali ke Daftar Pemesanan</a>
    </div>
</body>
</html>