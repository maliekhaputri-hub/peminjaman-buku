<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Peminjaman Buku')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-toggle { position: fixed; left: 10px; top: 10px; z-index: 1001; width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: var(--shadow-md); transition: var(--transition); }
        .sidebar-toggle:hover { transform: scale(1.1); box-shadow: var(--shadow-lg); }
        .sidebar { transition: transform 0.3s ease; }
        .sidebar.closed { transform: translateX(-100%); }
        .content.expanded { margin-left: 0; width: 100%; }
    </style>
</head>
<body>
    @if(Auth::check())
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <div class="main-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-book-open"></i>
                    <span>Perpustakaan</span>
                </a>
            </div>
            <ul class="sidebar-menu">
                @if(Auth::user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.books.index') }}" class="sidebar-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Buku</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.members.index') }}" class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Anggota</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.books.index') }}" class="sidebar-link {{ request()->routeIs('user.books.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Buku</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.transactions.index') }}" class="sidebar-link {{ request()->routeIs('user.transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Peminjaman Saya</span>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Anggota' }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('closed');
            content.classList.toggle('expanded');
            
            if (sidebar.classList.contains('closed')) {
                toggleBtn.querySelector('i').classList.remove('fa-bars');
                toggleBtn.querySelector('i').classList.add('fa-times');
            } else {
                toggleBtn.querySelector('i').classList.remove('fa-times');
                toggleBtn.querySelector('i').classList.add('fa-bars');
            }
        });
    });
    </script>
    @else
    <div class="auth-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
        
        <footer class="footer">
            <p>&copy; 2024 Aplikasi Peminjaman Buku. Dibuat dengan <i class="fas fa-heart text-danger"></i></p>
        </footer>
    </div>
    @endif
</body>
</html>
