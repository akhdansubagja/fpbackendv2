<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    {{-- Dependensi CSS & JS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #1e293b;
            --card-color: #2b374d;
            --primary-color: #00e5ff;
            --text-color: #e2e8f0;
            --text-muted-color: #94a3b8;
            --border-color: #4b5a72;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .main-layout { display: flex; }
        .sidebar { width: 250px; height: 100vh; position: fixed; top: 0; left: 0; background-color: var(--card-color); padding: 1.5rem; display: flex; flex-direction: column; z-index: 1000; }
        .main-content { margin-left: 250px; width: calc(100% - 250px); padding: 2rem; }
        .sidebar h4 { color: white; text-align: center; margin-bottom: 1.5rem; }
        .sidebar hr { border-color: var(--border-color); }
        .sidebar-nav a { color: var(--text-muted-color); text-decoration: none; display: flex; align-items: center; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 0.5rem; transition: all 0.2s ease-in-out; }
        .sidebar-nav a:hover { color: white; background-color: #38455c; }
        .sidebar-nav a.active { color: #0c131d; background-color: var(--primary-color); font-weight: 700; }
        .sidebar-footer { margin-top: auto; }

        .card { position: relative; border: none; border-radius: 15px; overflow: hidden; background: transparent; z-index: 5; }
        .card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; z-index: 6; background: conic-gradient(transparent, #00ffff, #ff00ff, #ffff00, #00ffff); animation: rotate-rgb 4s linear infinite; }
        .card::after { content: ''; position: absolute; inset: 3px; background: var(--card-color); border-radius: 12px; z-index: 7; }
        .card-header, .card-body, .card-title, .card-text, .list-group { position: relative; background: transparent; color: var(--text-color); z-index: 8; }
        .card-header, .card-body { padding: 1.5rem; }
        .fs-4 { font-size: 1.75rem !important; font-weight: 700; color: white; }

        .table { color: var(--text-color); }
        .table thead th { background-color: #38455c; color: white; border-color: var(--border-color); }
        .table tbody tr:hover { background-color: #313f54; }
        .table td, .table th { border-color: var(--border-color); vertical-align: middle; }

        .form-control, .form-select { background-color: #1e293b; color: var(--text-color); border: 1px solid var(--border-color); }
        .form-control:focus, .form-select:focus { background-color: #1e293b; color: var(--text-color); border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(0, 229, 255, 0.25); }
        
        /* ===== PERBAIKAN DAN PENAMBAHAN CSS ===== */
        
        /* Mengatur warna teks umum */
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            color: white; /* Judul dibuat putih terang */
        }
        
        .card-title {
             color: var(--text-muted-color);
             font-size: 1rem;
        }

        /* Mengatur warna teks modal secara eksplisit */
        .modal-content { 
            background-color: var(--card-color);
            color: var(--text-color); /* <<-- Kunci utama perbaikan */
        }
        .modal-header, .modal-footer { 
            border-color: var(--border-color); 
        }

        /* Mengatur warna elemen form di dalam modal */
        .form-label {
            color: var(--text-muted-color);
        }
        .form-text {
            color: var(--text-muted-color);
        }
        /* ======================================= */

        /* ===== TAMBAHAN STYLE UNTUK HALAMAN DETAIL ===== */
        .detail-header {
            border-bottom: 1px solid var(--border-color);
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 0.75rem 1rem;
            align-items: center;
        }
        .info-grid strong {
            font-weight: 600;
            color: var(--text-muted-color);
        }
        .info-grid span {
            color: var(--text-color);
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 1rem;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .image-container img:hover {
            transform: scale(1.03);
            border-color: var(--primary-color);
        }
        .back-link {
            display: inline-block;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            background-color: #38455c;
            color: white;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .back-link:hover {
            background-color: #495e7c;
        }
/* ============================================== */

        /* ===== TAMBAHAN STYLE UNTUK PAGINATION & ALERTS ===== */
        .pagination .page-item .page-link {
            background-color: var(--card-color);
            color: var(--text-muted-color);
            border-color: var(--border-color);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--bg-color);
            font-weight: 700;
        }
        .pagination .page-item:not(.active) .page-link:hover {
            background-color: #38455c;
        }
        .pagination .page-item.disabled .page-link {
            background-color: transparent;
            border-color: var(--border-color);
            color: #566882;
        }

        .alert-success {
            background-color: #0c4b33;
            color: #a3e9d0;
            border-color: #158055;
        }

        .table-borderless th, .table-borderless td {
            border: 0;
        }
/* ====================================================== */

        /* ===== TAMBAHAN STYLE UNTUK TOMBOL & GAMBAR ===== */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--bg-color);
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #00c4d6;
            border-color: #00c4d6;
            color: var(--bg-color);
        }
        .btn-secondary {
            background-color: #495e7c;
            border-color: #495e7c;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a7298;
            border-color: #5a7298;
        }
        .btn-warning {
            color: #000;
        }
        .img-fluid {
            border: 2px solid var(--border-color);
        }
/* ============================================== */

        /* ===== Perbaikan untuk Tombol Outline ===== */
        .btn-outline-light, .btn-outline-secondary {
            color: var(--text-muted-color);
            border-color: #566882; /* Warna border yang sedikit lebih terlihat */
        }

        .btn-outline-light:hover, .btn-outline-secondary:hover {
            color: var(--bg-color); /* Teks menjadi gelap saat di-hover */
            background-color: var(--text-color); /* Latar menjadi terang saat di-hover */
            border-color: var(--text-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            color: var(--bg-color);
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
/* ======================================= */

        /* ===== PERBAIKAN UNTUK LIST GROUP ===== */
        .list-group-item {
    background-color: transparent !important; /* Membuat latar belakangnya transparan */
    border-color: var(--border-color) !important;      /* Menyesuaikan warna garis pemisah */
    color: var(--text-color) !important;         /* <<-- BARIS INI DITAMBAHKAN (Membuat teks menjadi putih) */
}
/* ==================================== */

        /* ===== TAMBAHAN STYLE UNTUK LOGO SIDEBAR ===== */
        .sidebar-header {
            text-align: center;
            padding-bottom: 1.5rem;
        }
        .sidebar-header img {
            max-width: 80px; /* Atur ukuran maksimum logo di sini */
            margin-bottom: 1rem;
        }
        .sidebar-header h4 {
            margin-bottom: 0;
        }

        

        @media (max-width: 992px) { .sidebar { display: none; } .main-content { margin-left: 0; width: 100%; } }
        @keyframes rotate-rgb { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    
    {{-- Seluruh bagian <body> tidak ada perubahan --}}
    <div class="main-layout">
        <aside class="sidebar d-none d-lg-flex">
            <div>
                <div class="sidebar-header">
                    <img src="{{ asset('images/Icon.png') }}" alt="Logo">
                    <h4 class="text-white">Admin Panel</h4>
                </div>
                <hr>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">Manajemen Kendaraan</a>
                    <a href="{{ route('admin.rentals.index') }}" class="nav-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}">Manajemen Pemesanan</a>
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Manajemen Pembayaran</a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Manajemen Pengguna</a>
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
                        <span>Notifikasi</span>
                        <span id="notification-badge" class="badge rounded-pill" style="background-color: #dc3545; display: none;"></span>
                    </a>
                </nav>
            </div>
            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </div>
        </aside>
        <main class="main-content">
            <nav class="navbar mb-4 d-lg-none" style="background-color: var(--card-color); border-radius: 8px;">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="bi bi-list" style="color: white;"></i>
                    </button>
                    <h4 class="mb-0 text-white">Admin Panel</h4>
                </div>
            </nav>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="background-color: var(--card-color); color: white;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title d-flex align-items-center" id="mobileSidebarLabel">
                <img src="{{ asset('images/Icon.png') }}" alt="Logo" style="max-width: 80px; margin-right: 10px;">
                Admin Panel
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">Manajemen Kendaraan</a>
                <a href="{{ route('admin.rentals.index') }}" class="nav-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}">Manajemen Pemesanan</a>
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Manajemen Pembayaran</a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Manajemen Pengguna</a>
                <a href="{{ route('admin.notifications.index') }}" class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
                    <span>Notifikasi</span>
                    <span id="mobile-notification-badge" class="badge rounded-pill" style="background-color: #dc3545; display: none;"></span>
                </a>
            </nav>
            <div class="mt-auto">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ... kode javascript Anda ...
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pilih kedua elemen badge
            const desktopBadge = document.getElementById('notification-badge');
            const mobileBadge = document.getElementById('mobile-notification-badge');

            function fetchUnreadCount() {
                fetch('{{ route("admin.notifications.unreadCount") }}')
                    .then(response => response.json())
                    .then(data => {
                        const count = data.unread_count;
                        
                        // Buat fungsi untuk memperbarui satu badge
                        const updateBadge = (badgeElement) => {
                            if (badgeElement) {
                                if (count > 0) {
                                    badgeElement.textContent = count;
                                    badgeElement.style.display = 'inline-block';
                                } else {
                                    badgeElement.style.display = 'none';
                                }
                            }
                        };

                        // Perbarui kedua badge
                        updateBadge(desktopBadge);
                        updateBadge(mobileBadge);
                    })
                    .catch(error => console.error('Error fetching notification count:', error));
            }

            // Panggil fungsi saat halaman pertama kali dimuat
            fetchUnreadCount();

            // Panggil fungsi setiap 30 detik
            setInterval(fetchUnreadCount, 30000);
        });
    </script>
    @stack('scripts')
</body>
</html>