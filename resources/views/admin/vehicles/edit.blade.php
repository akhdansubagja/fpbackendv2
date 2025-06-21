@extends('layouts.admin')

@section('title', 'Edit Kendaraan')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Kendaraan: {{ $vehicle->merk }} {{ $vehicle->nama }}</h1>
    </div>

    {{-- Blok untuk menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! Ada beberapa masalah dengan input Anda.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- PENTING: Memberitahu Laravel ini adalah request UPDATE --}}

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="merk" class="form-label">Merk</label>
                            <input type="text" id="merk" name="merk" class="form-control" required
                                value="{{ old('merk', $vehicle->merk) }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama/Model Kendaraan</label>
                            <input type="text" id="nama" name="nama" class="form-control" required
                                value="{{ old('nama', $vehicle->nama) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga_sewa_harian" class="form-label">Harga Sewa / Hari</label>
                                <input type="number" id="harga_sewa_harian" name="harga_sewa_harian" class="form-control"
                                    required value="{{ old('harga_sewa_harian', $vehicle->harga_sewa_harian) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="security_deposit" class="form-label">Security Deposit</label>
                                <input type="number" id="security_deposit" name="security_deposit" class="form-control"
                                    required value="{{ old('security_deposit', $vehicle->security_deposit) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
                                <input type="number" id="jumlah_kursi" name="jumlah_kursi" class="form-control" required
                                    value="{{ old('jumlah_kursi', $vehicle->jumlah_kursi) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="transmisi" class="form-label">Transmisi</label>
                                <select id="transmisi" name="transmisi" class="form-select" required>
                                    <option value="manual" {{ old('transmisi', $vehicle->transmisi) == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="matic" {{ old('transmisi', $vehicle->transmisi) == 'matic' ? 'selected' : '' }}>Matic</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bahan_bakar" class="form-label">Bahan Bakar</label>
                                <select id="bahan_bakar" name="bahan_bakar" class="form-select" required>
                                    <option value="bensin" {{ old('bahan_bakar', $vehicle->bahan_bakar) == 'bensin' ? 'selected' : '' }}>Bensin</option>
                                    <option value="diesel" {{ old('bahan_bakar', $vehicle->bahan_bakar) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="listrik" {{ old('bahan_bakar', $vehicle->bahan_bakar) == 'listrik' ? 'selected' : '' }}>Listrik</option>
                                    <option value="hybrid" {{ old('bahan_bakar', $vehicle->bahan_bakar) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="has_ac" class="form-label">Fasilitas AC</label>
                                <select id="has_ac" name="has_ac" class="form-select" required>
                                    <option value="1" {{ old('has_ac', $vehicle->has_ac) ? 'selected' : '' }}>Ada AC
                                    </option>
                                    <option value="0" {{ !old('has_ac', $vehicle->has_ac) ? 'selected' : '' }}>Tidak Ada AC
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control"
                                required>{{ old('deskripsi', $vehicle->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="foto_utama" class="form-label">Ganti Foto Utama (Opsional)</label>
                            <img src="{{ $vehicle->foto_utama }}" alt="Foto saat ini" class="img-fluid rounded mb-2">
                            <input type="file" id="foto_utama" name="foto_utama" class="form-control">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">Tambah Foto Galeri (Opsional)</label>
                            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-header">
            Galeri Foto Saat Ini
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($vehicle->images as $image)
                    <div class="col-md-3 text-center mb-3">
                        <img src="{{ $image->path }}" alt="Gallery Image" class="img-fluid rounded mb-2">
                        <form action="{{ route('admin.vehicles.destroyImage', $image->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin hapus gambar ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus Gambar</button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted">Belum ada gambar galeri.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection