<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembayaran</title>
    {{-- Menggunakan style yang sama --}}
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .btn { background-color: #28a745; color: white; padding: 0.5rem 1rem; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #e9ecef; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Pembayaran</h1>
            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">Kembali ke Dashboard</a>
        </div>

        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID Bayar</th>
                    <th>ID Sewa</th>
                    <th>Penyewa</th>
                    <th>Jumlah Bayar</th>
                    <th>Status Bayar</th>
                    <th>Bukti Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->rental_id }}</td>
                        <td>{{ $payment->rental->user->name }}</td>
                        <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>{{ $payment->status_pembayaran }}</td>
                        <td>
                            @if($payment->bukti_pembayaran)
                                <a href="{{ $payment->bukti_pembayaran }}" target="_blank">Lihat Bukti</a>
                            @else
                                <i>Belum diunggah</i>
                            @endif
                        </td>
                        <td>
                            @if($payment->status_pembayaran == 'pending' && $payment->bukti_pembayaran)
                                <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_pembayaran" value="lunas">
                                    <button type="submit" class="btn">Set Lunas</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>