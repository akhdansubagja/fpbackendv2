@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Pembayaran</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID Bayar</th>
                    <th scope="col">Penyewa</th>
                    <th scope="col">Kendaraan</th>
                    <th scope="col">Jumlah Bayar</th>
                    <th scope="col">Status Bayar</th>
                    <th scope="col">Bukti Bayar</th>
                    <th scope="col" style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <th scope="row">{{ $payment->id }}</th>
                        <td>{{ $payment->rental->user->name }}</td>
                        <td>{{ $payment->rental->vehicle->merk }} {{ $payment->rental->vehicle->nama }}</td>
                        <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = '';
                                switch ($payment->status_pembayaran) {
                                    case 'pending':
                                        $statusClass = 'bg-warning text-dark';
                                        break;
                                    case 'lunas':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'gagal':
                                        $statusClass = 'bg-danger';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $payment->status_pembayaran }}</span>
                        </td>
                        {{-- ... (kode <thead> dan
                        <tbody> lainnya tidak berubah) ... --}}
                            <td>
                                {{-- Tampilkan tombol Lihat Bukti hanya jika ada file --}}
                                @if($payment->bukti_pembayaran)
                                    <a href="{{ $payment->bukti_pembayaran }}" target="_blank"
                                        class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-muted">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                {{-- PERBAIKAN: Form aksi sekarang selalu ditampilkan --}}
                                <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST"
                                    class="d-flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status_pembayaran" class="form-select form-select-sm">
                                        <option value="pending" {{ $payment->status_pembayaran == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="lunas" {{ $payment->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas
                                        </option>
                                        <option value="gagal" {{ $payment->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                </form>
                            </td>
                            {{-- ... (kode
                    </tr> dan @forelse lainnya tidak berubah) ... --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection