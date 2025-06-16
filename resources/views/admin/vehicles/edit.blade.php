<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kendaraan</title>
    {{-- (Style tidak perlu diubah) --}}
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 800px; margin: auto; background: white; padding: 2rem; border-radius: 8px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
        .current-img { max-width: 150px; margin-top: 10px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Kendaraan</h1>

        <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- PENTING: Memberitahu Laravel ini adalah request UPDATE --}}

            <div class="form-group">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" required value="{{ old('merk', $vehicle->merk) }}">
            </div>
            <div class="form-group">
                <label for="nama">Nama/Model Kendaraan</label>
                <input type="text" id="nama" name="nama" required value="{{ old('nama', $vehicle->nama) }}">
            </div>
            
            {{-- (Tambahkan 'value' untuk semua input lainnya) --}}
            <div class="form-group">
                <label for="harga_sewa_harian">Harga Sewa / Hari</label>
                <input type="number" id="harga_sewa_harian" name="harga_sewa_harian" required value="{{ old('harga_sewa_harian', $vehicle->harga_sewa_harian) }}">
            </div>
            <div class="form-group">
                <label for="jumlah_kursi">Jumlah Kursi</label>
                <input type="number" id="jumlah_kursi" name="jumlah_kursi" required value="{{ old('jumlah_kursi', $vehicle->jumlah_kursi) }}">
            </div>
            <div class="form-group">
                <label for="transmisi">Transmisi</label>
                <select id="transmisi" name="transmisi" required>
                    <option value="manual" {{ $vehicle->transmisi == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="matic" {{ $vehicle->transmisi == 'matic' ? 'selected' : '' }}>Matic</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bahan_bakar">Bahan Bakar</label>
                <select id="bahan_bakar" name="bahan_bakar" required>
                     <option value="bensin" {{ $vehicle->bahan_bakar == 'bensin' ? 'selected' : '' }}>Bensin</option>
                     <option value="diesel" {{ $vehicle->bahan_bakar == 'diesel' ? 'selected' : '' }}>Diesel</option>
                     <option value="listrik" {{ $vehicle->bahan_bakar == 'listrik' ? 'selected' : '' }}>Listrik</option>
                     <option value="hybrid" {{ $vehicle->bahan_bakar == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="has_ac">Fasilitas AC</label>
                <select id="has_ac" name="has_ac" required>
                    <option value="1" {{ $vehicle->has_ac ? 'selected' : '' }}>Ada AC</option>
                    <option value="0" {{ !$vehicle->has_ac ? 'selected' : '' }}>Tidak Ada AC</option>
                </select>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $vehicle->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <label for="foto_utama">Ganti Foto Utama (Opsional)</label>
                <img src="{{ $vehicle->foto_utama }}" alt="Foto saat ini" class="current-img">
                <input type="file" id="foto_utama" name="foto_utama">
            </div>
            
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>