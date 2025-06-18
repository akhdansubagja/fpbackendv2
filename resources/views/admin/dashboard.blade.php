@extends('layouts.admin')

{{-- Mengatur Judul Halaman --}}
@section('title', 'Admin Dashboard')

{{-- Konten Utama Halaman --}}
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <p>Selamat datang kembali, {{ Auth::user()->name }}!</p>

    {{-- Kartu Statistik --}}
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <p class="card-text fs-4">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Pending</h5>
                    <p class="card-text fs-4">{{ $stats['transaksi_pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Berjalan</h5>
                    <p class="card-text fs-4">{{ $stats['transaksi_berjalan'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kendaraan</h5>
                    <p class="card-text fs-4">{{ $stats['jumlah_kendaraan'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pengguna</h5>
                    <p class="card-text fs-4">{{ $stats['jumlah_pengguna'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kontainer untuk Grafik --}}
    <div class="card mt-4">
        <div class="card-header">
            Pendapatan 6 Bulan Terakhir
        </div>
        <div class="card-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
@endsection

{{-- Script Khusus untuk Halaman Ini --}}
@push('scripts')
<script>
    // Kode JavaScript untuk Chart.js tetap sama persis
    const ctx = document.getElementById('revenueChart');
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

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
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush