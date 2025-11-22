<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - Trading Platform</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'a36a14ee0812f3202f128755dbbcbfd5d760297c';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="sidebar-title">Menu</h5>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <div class="sidebar-section">
                <a href="{{ route('dashboard') }}" class="sidebar-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('show.investment') }}" class="sidebar-item">
                    <i class="fas fa-chart-pie"></i>
                    <span>My Subscriptions</span>
                </a>
                <a href="{{ route('show.deposit') }}" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Deposit</span>
                </a>
                <a href="{{ route('show.plans') }}" class="sidebar-item">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Subscribe</span>
                </a>
                <a href="{{ route('show.withdraw') }}" class="sidebar-item">
                    <i class="fas fa-minus-circle"></i>
                    <span>Withdraw</span>
                </a>
                <a href="{{ route('transactions') }}" class="sidebar-item">
                    <i class="fas fa-history"></i>
                    <span>Transactions</span>
                </a>
                <a href="{{ route('user.traders.index') }}" class="sidebar-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Copy Trading</span>
                </a>
                <a href="{{ route('user.signals.index') }}" class="sidebar-item">
                    <i class="fas fa-user-tie"></i>
                    <span>Signals</span>
                </a>
                <a href="{{ route('trade.index') }}" class="sidebar-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Trade</span>
                </a>
                <a href="{{ route('nfts.marketplace') }}" class="sidebar-item">
                    <i class="fas fa-cube"></i>
                    <span>NFTs</span>
                </a>
                 <a href="{{ route('dashboard.news') }}" class="sidebar-item">
                    <i class="fas fa-newspaper"></i>
                    <span>News</span>
                </a>

                {{-- <a href="#" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Referral</span>
                </a> --}}
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-section-title">Account Settings</div>
                <a href="{{ route('show.profile') }}" class="sidebar-item">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
                <a href="#" class="sidebar-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="top-header">
        <div class="container-fluid px-4">
            <div class="row align-items-center py-3">
                <div class="col-6">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Hamburger Menu -->
                        <button class="hamburger-btn" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <!--<div class="language-selector">-->
                        <!--    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">-->
                        <!--        <div class="flag-us me-2"></div>-->
                        <!--        <span class="fw-bold">EN</span>-->
                        <!--    </button>-->
                        <!--    <ul class="dropdown-menu">-->
                        <!--        <li><a class="dropdown-item" href="#">ðŸ‡ºðŸ‡¸ English</a></li>-->
                        <!--        <li><a class="dropdown-item" href="#">ðŸ‡ªðŸ‡¸ EspaÃ±ol</a></li>-->
                        <!--    </ul>-->
                        <!--</div>-->
                    </div>
                </div>
                <div class="col-6 text-end">
                    <!-- Profile Dropdown - Now visible on mobile too -->
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileToggle">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="profile-name">{{ auth()->user()->username }}</span>
                            <i class="fas fa-chevron-down profile-arrow"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-menu-item">
                                <a href="{{ route('show.profile') }}" class="text-decoration-none text-white">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="px-2">My Profile</span>
                                </a>
                            </div>
                            <div class="profile-menu-divider"></div>
                            <div class="profile-menu-item" onclick="document.getElementById('logout-form-profile').submit()">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </div>
                            <form id="logout-form-profile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-section">
        {{ $slot }}
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    <!-- Custom JavaScript -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
