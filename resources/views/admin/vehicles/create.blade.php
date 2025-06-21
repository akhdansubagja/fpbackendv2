<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan Baru</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 800px; margin: auto; background: white; padding: 2rem; border-radius: 8px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #007bff; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; }
        .btn-secondary { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Kendaraan Baru</h1>

        <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="merk">Merk</label>
                <input type="text" id="merk" name="merk" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama/Model Kendaraan</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="harga_sewa_harian">Harga Sewa / Hari</label>
                <input type="number" id="harga_sewa_harian" name="harga_sewa_harian" required>
            </div>
            <div class="form-group">
                <label for="security_deposit">Security Deposit</label>
                <input type="number" id="security_deposit" name="security_deposit" required value="0">
            </div>
            <div class="form-group">
                <label for="jumlah_kursi">Jumlah Kursi</label>
                <input type="number" id="jumlah_kursi" name="jumlah_kursi" required>
            </div>
            <div class="form-group">
                <label for="transmisi">Transmisi</label>
                <select id="transmisi" name="transmisi" required>
                    <option value="manual">Manual</option>
                    <option value="matic">Matic</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bahan_bakar">Bahan Bakar</label>
                <select id="bahan_bakar" name="bahan_bakar" required>
                    <option value="bensin">Bensin</option>
                    <option value="diesel">Diesel</option>
                    <option value="listrik">Listrik</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="has_ac">Fasilitas AC</label>
                <select id="has_ac" name="has_ac" required>
                    <option value="1">Ada AC</option>
                    <option value="0">Tidak Ada AC</option>
                </select>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="foto_utama">Foto Utama (Thumbnail)</label>
                <input type="file" id="foto_utama" name="foto_utama" required>
            </div>
            {{-- INPUT BARU UNTUK GALERI --}}
            <div class="form-group">
                <label for="gallery_images">Foto Galeri (Bisa pilih lebih dari satu)</label>
                <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" multiple>
            </div>
            
            <button type="submit" class="btn">Simpan Kendaraan</button>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>