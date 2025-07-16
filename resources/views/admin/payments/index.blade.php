@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Manajemen Pembayaran</h1>
    </div>

    {{-- Kartu utama yang membungkus tabel --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Bayar</th>
                            <th>Penyewa</th>
                            <th>Kendaraan</th>
                            <th>Jumlah Bayar</th>
                            <th>Status Bayar</th>
                            <th>Status Deposit</th>
                            <th>Bukti Bayar</th>
                            <th style="width: 25%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                {{-- Konten tabel tetap sama --}}
                                <th>{{ $payment->id }}</th>
                                <td>{{ $payment->rental->user->name }}</td>
                                <td>{{ $payment->rental->vehicle->merk }} {{ $payment->rental->vehicle->nama }}</td>
                                <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning text-dark',
                                            'lunas' => 'bg-success',
                                            'gagal' => 'bg-danger',
                                        ][$payment->status_pembayaran] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $payment->status_pembayaran }}</span>
                                </td>
                                <td>
                                    @php
                                        $depositStatusClass = [
                                            'ditahan' => 'bg-dark',
                                            'dikembalikan' => 'bg-success',
                                            'dipotong' => 'bg-warning text-dark',
                                        ][$payment->status_deposit] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $depositStatusClass }}">{{ $payment->status_deposit }}</span>
                                </td>
                                <td>
                                    @if($payment->bukti_pembayaran)
                                        <a href="{{ $payment->bukti_pembayaran }}" target="_blank" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <form action="{{ route('admin.payments.update-status', $payment->id) }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_pembayaran" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $payment->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="lunas" {{ $payment->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                            <option value="gagal" {{ $payment->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                        </select>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#depositModal-{{ $payment->id }}">
                                        Kelola Deposit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- BAGIAN KODE MODAL DIPINDAHKAN KE LUAR DARI CARD --}}
    {{-- ======================================================= --}}
    @foreach ($payments as $payment)
        <div class="modal fade" id="depositModal-{{ $payment->id }}" tabindex="-1" aria-labelledby="depositModalLabel-{{ $payment->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.payments.update-deposit', $payment->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="depositModalLabel-{{ $payment->id }}">Kelola Deposit untuk Pesanan #{{ $payment->rental_id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Total Security Deposit: <strong>Rp {{ number_format($payment->rental->vehicle->security_deposit, 0, ',', '.') }}</strong></p>
                            <hr style="border-color: var(--border-color);">
                            <div class="mb-3">
                                <label for="deposit_dikembalikan-{{ $payment->id }}" class="form-label">Jumlah Dikembalikan (Rp)</label>
                                <input type="number" class="form-control" id="deposit_dikembalikan-{{ $payment->id }}" name="deposit_dikembalikan" value="{{ $payment->deposit_dikembalikan ?? 0 }}" required>
                            </div>
                            <div class="form-text">
                                Masukkan jumlah uang yang dikembalikan ke penyewa. Jika ada potongan, masukkan sisa yang dikembalikan. Jika tidak ada yang dikembalikan, masukkan 0.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection