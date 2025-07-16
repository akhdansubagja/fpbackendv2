@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Dashboard</h1>
    </div>

    <p class="text-muted-color">Selamat datang kembali, {{ Auth::user()->name }}!</p>

    {{-- Kartu Statistik --}}
    <div class="row mt-4">
        @php
            $stats = [
                ['title' => 'Pendapatan Bersih', 'value' => 'Rp ' . number_format($financialStats['pendapatan_bersih'], 0, ',', '.')],
                ['title' => 'Total Uang Masuk', 'value' => 'Rp ' . number_format($financialStats['total_uang_masuk'], 0, ',', '.')],
                ['title' => 'Total Pengembalian', 'value' => 'Rp ' . number_format($financialStats['total_pengembalian_deposit'], 0, ',', '.')],
                ['title' => 'Transaksi Pending', 'value' => $summaryData['transaksi_pending']],
                ['title' => 'Transaksi Berjalan', 'value' => $summaryData['transaksi_berjalan']],
                ['title' => 'Jumlah Kendaraan', 'value' => $summaryData['jumlah_kendaraan']],
                ['title' => 'Jumlah Pengguna', 'value' => $summaryData['jumlah_pengguna']],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $stat['title'] }}</h5>
                    <p class="card-text fs-4">{{ $stat['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== KARTU KENDARAAN TERLARIS (DITAMBAHKAN KEMBALI) ===== --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 fw-bold">Kendaraan Terlaris</h6>
                </div>
                <div class="card-body">
                    {{-- Kita tidak perlu .list-group-flush karena .list-group-item sudah di-style --}}
                    <ul class="list-group">
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
        </div>
    </div>
    {{-- ============================================================= --}}

    {{-- Grafik --}}
    <div class="row">
        {{-- KARTU GRAFIK PENDAPATAN --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 fw-bold">Pendapatan 6 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU GRAFIK FREKUENSI --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 fw-bold">Frekuensi Pemesanan (30 Hari Terakhir)</h6>
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
    // Variabel warna untuk konsistensi
    const textColor = '#e2e8f0'; // var(--text-color)
    const gridColor = 'rgba(255, 255, 255, 0.1)';
    const tooltipBgColor = '#2b374d'; // var(--card-color)

    // Opsi konfigurasi umum untuk kedua chart
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: textColor },
                grid: { color: gridColor }
            },
            x: {
                ticks: { color: textColor },
                grid: { color: gridColor }
            }
        },
        plugins: {
            legend: {
                labels: { color: textColor }
            },
            // ===== BAGIAN BARU UNTUK TOOLTIP =====
            tooltip: {
                backgroundColor: tooltipBgColor,
                titleColor: '#ffffff',
                bodyColor: textColor,
                padding: 10,
                borderColor: gridColor,
                borderWidth: 1
            }
            // =====================================
        }
    };

    // Grafik Pendapatan Bersih
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan Bersih (Rp)',
                data: @json($chartData),
                backgroundColor: 'rgba(0, 229, 255, 0.5)',
                borderColor: 'rgba(0, 229, 255, 1)',
                borderWidth: 1
            }]
        },
        options: chartOptions // Menggunakan options umum
    });

    // Grafik Frekuensi Pemesanan
    new Chart(document.getElementById('frequencyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($frequencyLabels),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: @json($frequencyData),
                backgroundColor: 'rgba(28, 200, 138, 0.2)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            ...chartOptions, // Menggunakan options umum
            scales: {
                ...chartOptions.scales,
                y: {
                    ...chartOptions.scales.y,
                    ticks: { 
                        stepSize: 1, // Memastikan skala Y adalah bilangan bulat
                        color: textColor
                    }
                }
            }
        }
    });
</script>
@endpush