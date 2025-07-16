@extends('layouts.admin')

@section('title', 'Detail Pemesanan #' . $rental->id)

@section('content')
    <div class="detail-header pb-3 mb-4">
        <h1>Detail Pemesanan</h1>
        <p class="text-muted-color">ID Pesanan: <strong>#{{ $rental->id }}</strong> | Tanggal: {{ $rental->created_at->format('d F Y') }}</p>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri --}}
        <div class="col-lg-7">
            <div class="d-flex flex-column gap-4">
                {{-- Data Penyewa --}}
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Data Penyewa</h3>
                        <div class="info-grid">
                            <strong>Nama:</strong> <span>{{ $rental->user->name }}</span>
                            <strong>Email:</strong> <span>{{ $rental->user->email }}</span>
                            <strong>No. Telepon:</strong> <span>{{ $rental->user->nomor_telepon }}</span>
                            <strong>Alamat:</strong> <span>{{ $rental->user->alamat ?? 'Tidak ada alamat' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Pemesanan --}}
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Detail Pemesanan</h3>
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
                            </span>
                        </div>
                    </div>
                </div>
                 {{-- Kendaraan & Biaya --}}
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Kendaraan & Biaya</h3>
                        <div class="info-grid">
                            <strong>Merk:</strong> <span>{{ $rental->vehicle->merk }}</span>
                            <strong>Nama:</strong> <span>{{ $rental->vehicle->nama }}</span>
                            <strong>Harga/Hari:</strong> <span>Rp {{ number_format($rental->vehicle->harga_sewa_harian, 0, ',', '.') }}</span>
                            <hr style="grid-column: 1 / -1; border-color: var(--border-color); margin: 0.5rem 0;">
                            <strong>Biaya Sewa:</strong> <span>Rp {{ number_format($rental->total_harga - $rental->delivery_fee - $rental->vehicle->security_deposit, 0, ',', '.') }}</span>
                            <strong>Biaya Antar:</strong> <span>Rp {{ number_format($rental->delivery_fee, 0, ',', '.') }}</span>
                            <strong>Deposit:</strong> <span>Rp {{ number_format($rental->vehicle->security_deposit, 0, ',', '.') }}</span>
                            <hr style="grid-column: 1 / -1; border-color: var(--border-color); margin: 0.5rem 0;">
                            <strong>Total Harga:</strong> <strong class="text-white fs-5"><span>Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                {{-- Foto SIM --}}
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Foto SIM</h3>
                         <div class="image-container">
                            @if($rental->user->path_sim)
                                @php
                                    $correctSimPath = str_replace('public/', '', $rental->user->path_sim);
                                @endphp
                                <a href="{{ url('storage/' . $correctSimPath) }}" target="_blank">
                                    <img src="{{ url('storage/' . $correctSimPath) }}" alt="Foto SIM">
                                </a>
                            @else
                                <span class="text-muted-color"><i>Foto SIM tidak tersedia.</i></span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Detail Pembayaran --}}
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Detail Pembayaran</h3>
                        <div class="info-grid mb-4">
                            <strong>Metode Bayar:</strong> <span>{{ $rental->payment->metode_pembayaran ?? 'N/A' }}</span>
                            <strong>Tgl. Bayar:</strong>
                            <span>{{ $rental->payment->tanggal_pembayaran ? \Carbon\Carbon::parse($rental->payment->tanggal_pembayaran)->format('d F Y, H:i') : 'N/A' }}</span>
                            <strong>Status Bayar:</strong>
                            <span>
                                @if($rental->payment)
                                    @php
                                        $payStatusClass = [ 'pending' => 'bg-warning text-dark', 'lunas' => 'bg-success', 'gagal' => 'bg-danger' ][$rental->payment->status_pembayaran] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $payStatusClass }}">{{ $rental->payment->status_pembayaran }}</span>
                                @endif
                            </span>
                        </div>

                        <h4 class="card-title mb-3">Bukti Pembayaran</h4>
                        <div class="image-container">
                            @if($rental->payment && $rental->payment->bukti_pembayaran)
                                <a href="{{ $rental->payment->bukti_pembayaran }}" target="_blank">
                                    <img src="{{ $rental->payment->bukti_pembayaran }}" alt="Bukti Pembayaran">
                                </a>
                            @else
                                <span class="text-muted-color"><i>Belum ada bukti pembayaran.</i></span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ url()->previous() }}" class="back-link">Kembali</a>
    </div>
@endsection