@extends('layouts.admin')

@section('title', 'Tambah Kendaraan Baru')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" style="border-color: var(--border-color) !important;">
        <h1 class="h2">Tambah Kendaraan Baru</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="merk" class="form-label">Merk</label>
                        <input type="text" id="merk" name="merk" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama/Model Kendaraan</label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="harga_sewa_harian" class="form-label">Harga Sewa / Hari</label>
                        <input type="number" id="harga_sewa_harian" name="harga_sewa_harian" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="security_deposit" class="form-label">Security Deposit</label>
                        <input type="number" id="security_deposit" name="security_deposit" class="form-control" required value="0">
                    </div>
                    <div class="col-md-3">
                        <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
                        <input type="number" id="jumlah_kursi" name="jumlah_kursi" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="transmisi" class="form-label">Transmisi</label>
                        <select id="transmisi" name="transmisi" class="form-select" required>
                            <option value="manual">Manual</option>
                            <option value="matic">Matic</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bahan_bakar" class="form-label">Bahan Bakar</label>
                        <select id="bahan_bakar" name="bahan_bakar" class="form-select" required>
                            <option value="bensin">Bensin</option>
                            <option value="diesel">Diesel</option>
                            <option value="listrik">Listrik</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="has_ac" class="form-label">Fasilitas AC</label>
                        <select id="has_ac" name="has_ac" class="form-select" required>
                            <option value="1">Ada AC</option>
                            <option value="0">Tidak Ada AC</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="foto_utama" class="form-label">Foto Utama (Thumbnail)</label>
                        <input type="file" id="foto_utama" name="foto_utama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="gallery_images" class="form-label">Foto Galeri (Bisa lebih dari satu)</label>
                        <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
                        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection