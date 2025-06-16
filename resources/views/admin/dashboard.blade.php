<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 1200px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stat-card h3 { margin-top: 0; }
        .chart-container { background: white; padding: 2rem; margin-top: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .logout-btn { background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Pendapatan</h3>
                <p>Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
            </div>
            <div class="stat-card">
                <h3>Transaksi Pending</h3>
                <p>{{ $stats['transaksi_pending'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Transaksi Berjalan</h3>
                <p>{{ $stats['transaksi_berjalan'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Kendaraan</h3>
                <p>{{ $stats['jumlah_kendaraan'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Pengguna</h3>
                <p>{{ $stats['jumlah_pengguna'] }}</p>
            </div>
        </div>

        <div class="chart-container">
            <h3>Pendapatan 6 Bulan Terakhir</h3>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <script>
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
</body>
</html>