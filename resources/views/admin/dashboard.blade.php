@extends('layouts.admin')

{{-- Judul Halaman --}}
@section('title', 'Admin Dashboard')

{{-- Konten Utama --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <p>Selamat datang kembali, {{ Auth::user()->name }}!</p>

    {{-- Kartu Statistik --}}
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <p class="card-text fs-4">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Pending</h5>
                    <p class="card-text fs-4">{{ $stats['transaksi_pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Berjalan</h5>
                    <p class="card-text fs-4">{{ $stats['transaksi_berjalan'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kendaraan</h5>
                    <p class="card-text fs-4">{{ $stats['jumlah_kendaraan'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pengguna</h5>
                    <p class="card-text fs-4">{{ $stats['jumlah_pengguna'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kendaraan Terlaris --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kendaraan Terlaris</h6>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse ($topVehicles as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->merk }} {{ $item->nama }}
                        <span class="badge bg-primary rounded-pill">{{ $item->rental_count }}x disewa</span>
                    </li>
                @empty
                    <li class="list-group-item">Belum ada data penyewaan.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Grafik Pendapatan & Frekuensi --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Pendapatan 6 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Frekuensi Pemesanan (30 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="frequencyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        const ctx = document.getElementById('revenueChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const frequencyCtx = document.getElementById('frequencyChart').getContext('2d');
        new Chart(frequencyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($frequencyLabels) !!},
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: {!! json_encode($frequencyData) !!},
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush
