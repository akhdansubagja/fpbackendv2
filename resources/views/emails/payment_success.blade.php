<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran Berhasil</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        .container { padding: 20px; }
        .header { font-size: 24px; color: #064420; }
        .details { margin-top: 20px; padding: 15px; border-left: 4px solid #28a745; background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Pembayaran Berhasil!</h1>
        <p>Halo, <strong>{{ $rental->user->name }}</strong>!</p>
        <p>Terima kasih! Pembayaran Anda untuk pesanan sewa mobil telah kami terima dan konfirmasi.</p>

        <div class="details">
            <h3>Detail Pesanan:</h3>
            <ul>
                <li><strong>ID Pesanan:</strong> #{{ $rental->id }}</li>
                <li><strong>Mobil:</strong> {{ $rental->vehicle->merk }} {{ $rental->vehicle->nama }}</li>
                <li><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($rental->waktu_sewa)->format('d F Y, H:i') }}</li>
                <li><strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($rental->waktu_kembali)->format('d F Y, H:i') }}</li>
                <li><strong>Total Biaya:</strong> Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</li>
            </ul>
        </div>

        <p style="margin-top: 20px;">Anda bisa melihat detail pesanan Anda kapan saja melalui aplikasi GoRentAll.</p>
        <p>Terima kasih telah menggunakan layanan kami.</p>
        <br>
        <p>Salam hangat,</p>
        <p>Tim GoRentAll</p>
    </div>
</body>
</html>