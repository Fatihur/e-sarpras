<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'e-Sarpras')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary-color: #78C841; --secondary-color: #5fb030; --primary-dark: #4a9928; }
        html { height: 100%; height: -webkit-fill-available; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f9f2; overflow-x: hidden; min-height: 100%; min-height: 100dvh; }
        .sidebar { width: var(--sidebar-width); height: 100vh; height: 100dvh; position: fixed; left: 0; top: 0; bottom: 0; background: #2d5016; z-index: 1050; transition: transform 0.3s ease; display: flex; flex-direction: column; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0; }
        .sidebar-header h4 { color: #fff; font-weight: 700; margin: 0; font-size: 1.25rem; }
        .sidebar-nav { padding: 1rem 0; flex: 1; overflow-y: auto; overflow-x: hidden; }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5); }
        .nav-section { padding: 0.5rem 1.5rem; color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.5rem; }
        .sidebar-nav .nav-link { color: rgba(255,255,255,0.8); padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; border-left: 3px solid transparent; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; border-left-color: #fff; }
        .sidebar-nav .nav-link i { font-size: 1.1rem; width: 24px; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left 0.3s ease; }
        .top-navbar { background: #fff; padding: 1rem 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .content-wrapper { padding: 1.5rem; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 1rem 1.25rem; font-weight: 600; }
        .stat-card { border-radius: 12px; padding: 1.25rem; color: #fff; }
        .stat-card.primary { background: #78C841; }
        .stat-card.success { background: #16a34a; }
        .stat-card.warning { background: #ca8a04; }
        .stat-card.danger { background: #dc2626; }
        .stat-card.info { background: #0d9488; }
        .stat-card .stat-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.875rem; opacity: 0.9; }
        .btn-primary { background: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .bg-primary { background-color: var(--primary-color) !important; }
        .btn-outline-primary { color: var(--primary-color); border-color: var(--primary-color); }
        .btn-outline-primary:hover { background: var(--primary-color); border-color: var(--primary-color); }
        .text-primary { color: var(--primary-color) !important; }
        a { color: var(--primary-color); }
        a:hover { color: var(--primary-dark); }
        .table th { font-weight: 600; color: #475569; background: #f8fafc; }
        .badge { font-weight: 500; padding: 0.4em 0.8em; }
        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length select { min-width: 80px; }
        .dataTables_wrapper .dataTables_filter input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; }
        .dataTables_wrapper .dataTables_info { color: #64748b; font-size: 0.875rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 6px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #fff !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #e2e8f0 !important; border-color: #e2e8f0 !important; }
        table.dataTable { border-collapse: collapse !important; }
        table.dataTable thead th { border-bottom: 2px solid #e2e8f0 !important; }
        table.dataTable tbody td { border-bottom: 1px solid #f1f5f9 !important; vertical-align: middle; }
        div.dataTables_wrapper div.dataTables_length { margin-bottom: 1rem; }
        div.dataTables_wrapper div.dataTables_filter { margin-bottom: 1rem; }
        @media (max-width: 767px) {
            .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { text-align: left !important; }
            .dataTables_wrapper .dataTables_filter input { width: 100% !important; margin-left: 0 !important; }
            .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { text-align: center !important; margin-top: 0.5rem; }
        }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; height: 100dvh; background: rgba(0,0,0,0.5); z-index: 1040; }
        .sidebar-overlay.show { display: block; }
        @media (max-width: 991px) { 
            .sidebar { 
                transform: translateX(-100%); 
                width: 280px;
                height: 100%;
                min-height: 100vh;
                min-height: -webkit-fill-available;
            } 
            .sidebar.show { transform: translateX(0); } 
            .main-content { margin-left: 0; } 
            html { height: -webkit-fill-available; }
        }
        @media (max-width: 767px) {
            .content-wrapper { padding: 1rem; }
            .top-navbar { padding: 0.75rem 1rem; }
            .top-navbar .d-flex { flex-wrap: wrap; gap: 0.5rem !important; }
            .top-navbar .badge { display: none; }
            .stat-card { padding: 1rem; }
            .stat-card .stat-value { font-size: 1.5rem; }
            .stat-card .stat-icon { font-size: 2rem; }
            .card-header { padding: 0.75rem 1rem; }
            .card-body { padding: 1rem; }
            .table { font-size: 0.875rem; }
            .table th, .table td { padding: 0.5rem; white-space: nowrap; }
            .btn { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
            .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
            h4 { font-size: 1.25rem; }
            .d-flex.justify-content-between { flex-direction: column; gap: 1rem; align-items: flex-start !important; }
            .d-flex.justify-content-between > div { width: 100%; display: flex; gap: 0.5rem; }
            .d-flex.justify-content-between > a.btn { width: 100%; }
            .form-control, .form-select { font-size: 0.875rem; }
            .row.g-3 > [class*="col-"] { margin-bottom: 0.5rem; }
            .alert { font-size: 0.875rem; padding: 0.75rem; }
        }
        @media (max-width: 575px) {
            .content-wrapper { padding: 0.75rem; }
            .table-responsive { margin: 0 -1rem; padding: 0 1rem; }
            .btn-group-mobile { display: flex; flex-direction: column; gap: 0.5rem; }
            .btn-group-mobile .btn { width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-building me-2"></i>e-Sarpras</h4>
            <button class="btn btn-link text-white d-lg-none p-0" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            @if(auth()->user()->isAdmin())
            <div class="nav-section">Master Data</div>
            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> Barang
            </a>
            <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori
            </a>
            <a href="{{ route('ruangan.index') }}" class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Ruangan
            </a>
            <a href="{{ route('gedung.index') }}" class="nav-link {{ request()->routeIs('gedung.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Gedung
            </a>
            <a href="{{ route('lahan.index') }}" class="nav-link {{ request()->routeIs('lahan.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> Lahan
            </a>
            @endif

            <div class="nav-section">Transaksi</div>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('barang-masuk.index') }}" class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
            </a>
            <a href="{{ route('barang-keluar.index') }}" class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-up"></i> Barang Keluar
            </a>
            @endif
            
            @if(auth()->user()->isAdmin() || auth()->user()->isManajemen())
            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Peminjaman
            </a>
            <a href="{{ route('barang-rusak.index') }}" class="nav-link {{ request()->routeIs('barang-rusak.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Barang Rusak
            </a>
            {{-- <a href="{{ route('scan.index') }}" class="nav-link {{ request()->routeIs('scan.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Scan QR
            </a> --}}
            @endif

            <a href="{{ route('barang-ruangan.index') }}" class="nav-link {{ request()->routeIs('barang-ruangan.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Barang Ruangan
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
            <div class="nav-section">Laporan</div>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <div class="nav-section">Pengaturan</div>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User
            </a>
            <a href="{{ route('telegram.index') }}" class="nav-link {{ request()->routeIs('telegram.*') ? 'active' : '' }}">
                <i class="bi bi-telegram"></i> Telegram
            </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <nav class="top-navbar">
            <button class="btn btn-link d-lg-none p-0" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="d-none d-lg-block"></div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->nama }}</span>
                <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </nav>

        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        
        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
