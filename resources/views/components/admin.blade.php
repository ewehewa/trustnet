<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrustNetX - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('admin/styles.css') }}">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo-section">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <span>TrustNetX</span>
            </div>
            
            <div class="header-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <div class="user-profile" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                    <i class="fas fa-chevron-down ms-2"></i>
                </div>

                <!-- Dropdown menu with logout form -->
                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li> --}}
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                            @csrf
                            <a href="#" class="dropdown-item"
                               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-item-text">All Users</span>
            </a>
            <a href="{{ route('admin.deposits.all') }}" class="menu-item">
                <i class="fas fa-arrow-down"></i>
                <span class="menu-item-text">Deposits</span>
            </a>
            <a href="{{ route('admin.withdrawals') }}" class="menu-item">
                <i class="fas fa-arrow-up"></i>
                <span class="menu-item-text">Withdrawals</span>
            </a>
            <a href="{{ route('admin.plans') }}" class="menu-item">
                <i class="fas fa-chart-pie"></i>
                <span class="menu-item-text">Add Investment Plans</span>
            </a>
            <a href="{{ route('admin.show.plans') }}" class="menu-item">
                <i class="fas fa-chart-pie"></i>
                <span class="menu-item-text">All Investment Plans</span>
            </a>
            <a href="{{ route('admin.wallet.update') }}" class="menu-item">
                <i class="fas fa-wallet"></i>
                <span class="menu-item-text">Update Wallet</span>
            </a>
            <a href="{{ route('admin.wallets') }}" class="menu-item"> {{-- NEW ITEM --}}
                <i class="fas fa-wallet"></i>
                <span class="menu-item-text">My Wallets</span>
            </a>
            <a href="{{ route('admin.password.change') }}" class="menu-item">
                <i class="fas fa-wallet"></i>
                <span class="menu-item-text">change Password</span>
            </a>
            <a href="{{ route('admin.mail.create') }}" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span class="menu-item-text">Send Email</span>
            </a>
             <a href="{{ route('admin.traders.create') }}" class="menu-item">
                <i class="fas fa-user-tie"></i>
                <span class="menu-item-text">Add Trader</span>
            </a>
            <a href="{{ route('admin.traders.index') }}" class="menu-item">
                <i class="fas fa-users"></i>
                <span class="menu-item-text">All Traders</span>
            </a>
            <a href="{{ route('admin.nfts.create') }}" class="menu-item">
                <i class="fas fa-cogs"></i>
                <span class="menu-item-text">Add NFT</span>
            </a>
            <a href="{{ route('admin.signals.create') }}" class="menu-item">
                <i class="fas fa-cogs"></i>
                <span class="menu-item-text">Add Signals</span>
            </a>
        </nav>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content" id="mainContent">
        {{ $slot }}
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
  <script>
    // Initialize Toastr with default options
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000"
    };
  </script>
    <script src="{{ asset('admin/script.js') }}"></script>
</body>
</html>
