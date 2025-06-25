<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan #{{ $rental->id }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .header h1 {
            margin: 0;
            color: #1a202c;
            font-size: 2rem;
        }

        .header p {
            margin: 0.25rem 0 0;
            color: #718096;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }

        .card {
            background: #fff;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .card h3 {
            margin-top: 0;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 0.75rem;
            color: #2d3748;
            font-size: 1.25rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 0.75rem;
            align-items: center;
        }

        .info-grid strong {
            font-weight: 600;
            color: #4a5568;
        }

        .info-grid span {
            color: #718096;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 1rem;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .image-container img:hover {
            transform: scale(1.03);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .status-pending {
            background-color: #feebc8;
            color: #744210;
        }

        .status-lunas {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .status-active {
            background-color: #bee3f8;
            color: #2a4365;
        }

        .status-selesai {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .footer {
            margin-top: 2rem;
            text-align: center;
        }

        .back-link {
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            background-color: #4a5568;
            color: white;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .back-link:hover {
            background-color: #2d3748;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Detail Pemesanan</h1>
            <p>ID Pesanan: <strong>#{{ $rental->id }}</strong> | Tanggal: {{ $rental->created_at->format('d F Y') }}</p>
        </div>

        <div class="grid-container">
            <div class="card">
                <h3>Data Penyewa</h3>
                <div class="info-grid">
                    <strong>Nama:</strong> <span>{{ $rental->user->name }}</span>
                    <strong>Email:</strong> <span>{{ $rental->user->email }}</span>
                    <strong>No. Telepon:</strong> <span>{{ $rental->user->nomor_telepon }}</span>
                    <strong>Alamat:</strong> <span>{{ $rental->user->alamat ?? 'Tidak ada alamat' }}</span>
                </div>
                <div class="image-container">
                    @if($rental->user->path_sim)
                        @php
                            $correctSimPath = str_replace('public/', '', $rental->user->path_sim);
                        @endphp
                        <a href="{{ url('storage/' . $correctSimPath) }}" target="_blank">
                            <img src="{{ url('storage/' . $correctSimPath) }}" alt="Foto SIM">
                        </a>
                    @else
                        <span><i>Foto SIM tidak tersedia.</i></span>
                    @endif
                </div>
            </div>

            <div class="card">
                <h3>Detail Pembayaran</h3>
                <div class="info-grid">
                    <strong>Metode Bayar:</strong> <span>{{ $rental->payment->metode_pembayaran ?? 'N/A' }}</span>
                    <strong>Tgl. Bayar:</strong>
                    <span>{{ $rental->payment->tanggal_pembayaran ? \Carbon\Carbon::parse($rental->payment->tanggal_pembayaran)->format('d F Y, H:i') : 'N/A' }}</span>
                    <strong>Status Bayar:</strong>
                    <span>
                        @if($rental->payment)
                            <span
                                class="status-badge status-{{ strtolower($rental->payment->status_pembayaran) }}">{{ $rental->payment->status_pembayaran }}</span>
                        @endif
                    </span>
                </div>
                <h3 style="margin-top: 2rem;">Bukti Pembayaran</h3>
                <div class="image-container">
                    @if($rental->payment && $rental->payment->bukti_pembayaran)
                        <a href="{{ $rental->payment->bukti_pembayaran }}" target="_blank">
                            <img src="{{ $rental->payment->bukti_pembayaran }}" alt="Bukti Pembayaran">
                        </a>
                    @else
                        <span><i>Belum ada bukti pembayaran.</i></span>
                    @endif
                </div>
            </div>

            <div class="card">
                <h3>Detail Pemesanan</h3>
                <div class="info-grid">
                    <strong>Waktu Sewa:</strong>
                    <span>{{ \Carbon\Carbon::parse($rental->waktu_sewa)->format('d F Y, H:i') }}</span>
                    <strong>Waktu Kembali:</strong>
                    <span>{{ \Carbon\Carbon::parse($rental->waktu_kembali)->format('d F Y, H:i') }}</span>
                    <strong>Opsi Pengantaran:</strong>
                    <span>{{ $rental->delivery_option === 'delivered' ? 'Diantar ke Lokasi' : 'Ambil Sendiri' }}</span>
                    <strong>Alamat Antar:</strong> <span>{{ $rental->delivery_address ?? 'N/A' }}</span>
                    <strong>Status Pesanan:</strong>
                    <span>
                        <span
                            class="status-badge status-{{ strtolower($rental->status_pemesanan) }}">{{ $rental->status_pemesanan }}</span>
                    </span>
                </div>
            </div>

            <div class="card">
                <h3>Kendaraan & Biaya</h3>
                <div class="info-grid">
                    <strong>Merk:</strong> <span>{{ $rental->vehicle->merk }}</span>
                    <strong>Nama:</strong> <span>{{ $rental->vehicle->nama }}</span>
                    <strong>Harga/Hari:</strong> <span>Rp
                        {{ number_format($rental->vehicle->harga_sewa_harian, 0, ',', '.') }}</span>
                    <hr style="grid-column: 1 / -1; border-top: 1px solid #eee; margin: 0.5rem 0;">
                    <strong>Biaya Sewa:</strong> <span>Rp
                        {{ number_format($rental->total_harga - $rental->delivery_fee - $rental->vehicle->security_deposit, 0, ',', '.') }}</span>
                    <strong>Biaya Antar:</strong> <span>Rp
                        {{ number_format($rental->delivery_fee, 0, ',', '.') }}</span>
                    <strong>Deposit:</strong> <span>Rp
                        {{ number_format($rental->vehicle->security_deposit, 0, ',', '.') }}</span>
                    <hr style="grid-column: 1 / -1; border-top: 1px solid #eee; margin: 0.5rem 0;">
                    <strong>Total Harga:</strong> <strong><span>Rp
                            {{ number_format($rental->total_harga, 0, ',', '.') }}</span></strong>
                </div>
                <div class="image-container">
                    <img src="{{ $rental->vehicle->foto_utama }}" alt="Foto Utama Kendaraan">
                </div>
            </div>
        </div>

        <div class="footer">
            <a href="{{ url()->previous() }}" class="back-link">Kembali</a>
        </div>
    </div>
</body>

</html>