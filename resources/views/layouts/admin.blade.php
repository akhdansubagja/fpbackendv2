<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Script Chart.js kita letakkan di head agar siap digunakan --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        /* Hanya berlaku untuk layar besar (desktop) */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                width: 250px;
                padding-top: 1rem;
                background-color: #343a40;
                color: white;
            }
            .main-content {
                margin-left: 250px;
            }
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 0.75rem 1.5rem;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white;
            background-color: #495057;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar d-none d-md-block p-3">
            <h4 class="text-white">Admin Panel</h4>
            <hr class="text-secondary">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.index') ? 'active' : '' }}">Manajemen Kendaraan</a>
            <a href="{{ route('admin.rentals.index') }}" class="nav-link {{ request()->routeIs('admin.rentals.index') ? 'active' : '' }}">Manajemen Pemesanan</a>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.index') ? 'active' : '' }}">Manajemen Pembayaran</a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">Notifikasi</a>
            <hr class="text-secondary">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>

        <div class="offcanvas offcanvas-start bg-dark text-white d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileSidebarLabel">Admin Panel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                {{-- Konten navigasi untuk mobile --}}
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('admin.vehicles.index') }}" class="nav-link">Manajemen Kendaraan</a>
                <a href="{{ route('admin.rentals.index') }}" class="nav-link">Manajemen Pemesanan</a>
                <a href="{{ route('admin.payments.index') }}" class="nav-link">Manajemen Pembayaran</a>
                <a href="{{ route('admin.notifications.index') }}" class="nav-link">Notifikasi</a>
                <hr class="text-secondary">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </div>
        </div>

        <main class="main-content flex-grow-1 p-4">
            <nav class="navbar navbar-light bg-light mb-3 d-md-none">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="bi bi-list"></i> Menu
                    </button>
                    <span class="navbar-brand mb-0 h1">Admin</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>