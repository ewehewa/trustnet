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
{{-- <script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = 'a36a14ee0812f3202f128755dbbcbfd5d760297c';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript> --}}
</head>
<body>
    <style>
        
/* Live Analysis / sidebar dropdown styling */
.sidebar-dropdown {
  display: block;
  width: 100%;
}

/* The visible top-level toggle (Logout, Live Analysis, etc.) */
.sidebar-dropdown .sidebar-item,
.sidebar-dropdown .sidebar-dropdown-toggle {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 12px 18px;
  color: #cbd5e1; /* soft text */
  text-decoration: none;
  font-size: 14px;
  border-radius: 6px;
  cursor: pointer;
}

/* Icon area on the left for the top item */
.sidebar-dropdown .sidebar-dropdown-toggle i.fas {
  margin-right: 10px;
  width: 20px;
  text-align: center;
}

/* The chevron on the right */
.sidebar-dropdown .dropdown-arrow {
  transition: transform .25s ease;
  color: #9ca3af;
}

/* Submenu hidden by default */
.sidebar-dropdown .sidebar-submenu {
  display: none;
  flex-direction: column;
  padding-left: 6px;         /* slight indent */
  margin-top: 6px;
  gap: 4px;
}

/* Show submenu when parent is open */
.sidebar-dropdown.open .sidebar-submenu {
  display: flex;
}

/* Individual submenu items */
.sidebar-dropdown .sidebar-subitem {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 18px;
  padding-left: 36px;        /* deeper indent for nested look */
  color: #cbd5e1;
  text-decoration: none;
  font-size: 13.5px;
  border-radius: 6px;
}

/* Hover/focus styles */
.sidebar-dropdown .sidebar-subitem:hover,
.sidebar-dropdown .sidebar-dropdown-toggle:hover {
  background: rgba(255,255,255,0.03);
  color: #ffffff;
}

/* Active/selected state */
.sidebar-dropdown .sidebar-subitem.active,
.sidebar-dropdown .sidebar-dropdown-toggle.active {
  background: rgba(255,255,255,0.04);
  color: #fff;
}

/* Small icon for subitems (if you use <i> tags) */
.sidebar-dropdown .sidebar-subitem i {
  width: 20px;
  text-align: center;
  color: #f8fafc;
  opacity: 0.9;
}

/* Rotate chevron when open */
.sidebar-dropdown.open .dropdown-arrow {
  transform: rotate(-180deg);
  color: #fff;
}

/* Mobile tweaks — keep submenu readable on small screens */
@media (max-width: 767.98px) {
  .sidebar-dropdown .sidebar-subitem {
    padding-left: 28px;
    font-size: 14px;
  }
}

/* Fix spacing for dropdown items like Live Analysis */
.sidebar-dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 10px;  /* Controls space between icon and text */
}

.sidebar-dropdown-toggle i:first-child {
    margin-right: 8px; /* Icon spacing */
}

.sidebar-dropdown-toggle .dropdown-arrow {
    margin-left: auto; /* Keeps chevron on the far right */
}

/* Make header items fit on mobile */
.top-header .header-btn {
    font-size: 0.7rem !important;
    padding: 3px 6px !important;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.profile-btn {
    font-size: 0.75rem !important;
    padding: 3px 6px !important;
    white-space: nowrap;
}

.profile-avatar {
    width: 25px !important;
    height: 25px !important;
    font-size: 0.8rem !important;
}

/* Prevent breaking on mobile */
.top-header .d-flex {
    flex-wrap: nowrap !important;
}

/* Shrink Entire Right Side on Small Screens */
@media (max-width: 480px) {
    .top-header .header-btn {
        font-size: 0.65rem !important;
        padding: 2px 4px !important;
    }

    .profile-name {
        display: none; /* hide long username on very small screens */
    }

    .profile-btn {
        padding: 2px 4px !important;
    }

    .profile-avatar {
        width: 22px !important;
        height: 22px !important;
    }

    .top-header .col-6.text-end {
        gap: 4px !important;
    }
}





    </style>
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
                <a href="{{ route('show.profile') }}" class="sidebar-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Account Settings</span>
                </a>
                <a href="#" class="sidebar-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <!-- Live Analysis Menu Group -->
                <div class="sidebar-dropdown">
                    <a class="sidebar-item sidebar-dropdown-toggle">
                        <i class="fas fa-chart-bar"></i>
                        <span>Live Analysis</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>

                    <div class="sidebar-submenu">
                        <a href="{{ route('dashboard.technical') }}" class="sidebar-subitem">
                            <i class="fas fa-chart-line"></i>
                            <span>Technical Analysis</span>
                        </a>

                        <a href="{{ route('dashboard.livecharts') }}" class="sidebar-subitem">
                            <i class="fas fa-chart-area"></i>
                            <span>Live Market Chart</span>
                        </a>

                        <a href="{{ route('dashboard.calendar') }}" class="sidebar-subitem">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Market Calendar</span>
                        </a>
                    </div>
                </div>

                
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

                <div class="col-6 text-end d-flex justify-content-end align-items-center gap-2 flex-wrap flex-md-nowrap">
    <!-- Deposit Button -->
    <a href="{{ route('show.deposit') }}" class="btn btn-success btn-sm header-btn">
        <i class="fas fa-plus-circle me-1"></i> Deposit
    </a>

    <!-- Mail Us Button -->
    <a href="mailto:support@trustnetx.com" class="btn btn-info btn-sm header-btn">
        <i class="fas fa-envelope me-1"></i> Mail Us
    </a>

    <!-- Profile Dropdown -->
    <div class="profile-dropdown d-flex align-items-center ms-2">
        <button class="profile-btn d-flex align-items-center gap-1" id="profileToggle">
            <span class="profile-avatar">
                <i class="fas fa-user"></i>
            </span>
            <span class="profile-name">{{ auth()->user()->username }}</span>
            <i class="fas fa-chevron-down profile-arrow"></i>
        </button>

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
    <script>
        // Trading Chart Implementation
document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("tradingChart")
  if (canvas) {
    const ctx = canvas.getContext("2d")

    function resizeCanvas() {
      const container = canvas.parentElement
      canvas.width = container.offsetWidth - 32
      canvas.height = container.offsetHeight - 32
      drawChart()
    }

    const chartData = [
      { x: 0, y: 150, volume: 20 },
      { x: 50, y: 120, volume: 35 },
      { x: 100, y: 140, volume: 25 },
      { x: 150, y: 80, volume: 45 },
      { x: 200, y: 100, volume: 30 },
      { x: 250, y: 60, volume: 55 },
      { x: 300, y: 90, volume: 40 },
      { x: 350, y: 40, volume: 60 },
      { x: 400, y: 70, volume: 35 },
    ]

    function drawChart() {
      const width = canvas.width
      const height = canvas.height

      ctx.clearRect(0, 0, width, height)

      const volumeHeight = 60
      const barWidth = width / chartData.length

      chartData.forEach((point, index) => {
        const barHeight = (point.volume / 60) * volumeHeight
        const x = index * barWidth + barWidth / 4
        const y = height - barHeight

        ctx.fillStyle = index % 2 === 0 ? "#10b981" : "#ef4444"
        ctx.fillRect(x, y, barWidth / 2, barHeight)
      })

      ctx.beginPath()
      ctx.strokeStyle = "#3b82f6"
      ctx.lineWidth = 2

      chartData.forEach((point, index) => {
        const x = (point.x / 400) * width
        const y = ((200 - point.y) / 200) * (height - volumeHeight - 20) + 10

        if (index === 0) ctx.moveTo(x, y)
        else ctx.lineTo(x, y)
      })

      ctx.stroke()

      ctx.beginPath()
      ctx.fillStyle = "rgba(59, 130, 246, 0.1)"

      chartData.forEach((point, index) => {
        const x = (point.x / 400) * width
        const y = ((200 - point.y) / 200) * (height - volumeHeight - 20) + 10

        if (index === 0) ctx.moveTo(x, y)
        else ctx.lineTo(x, y)
      })

      const lastPoint = chartData[chartData.length - 1]
      const lastX = (lastPoint.x / 400) * width
      const firstX = (chartData[0].x / 400) * width

      ctx.lineTo(lastX, height - volumeHeight)
      ctx.lineTo(firstX, height - volumeHeight)
      ctx.closePath()
      ctx.fill()

      ctx.strokeStyle = "rgba(156, 163, 175, 0.2)"
      ctx.lineWidth = 1

      for (let i = 1; i < 5; i++) {
        const y = (i / 5) * (height - volumeHeight - 20) + 10
        ctx.beginPath()
        ctx.moveTo(0, y)
        ctx.lineTo(width, y)
        ctx.stroke()
      }

      for (let i = 1; i < 8; i++) {
        const x = (i / 8) * width
        ctx.beginPath()
        ctx.moveTo(x, 10)
        ctx.lineTo(x, height - volumeHeight)
        ctx.stroke()
      }
    }

    resizeCanvas()
    window.addEventListener("resize", resizeCanvas)

    setInterval(() => {
      const lastIndex = chartData.length - 1
      chartData[lastIndex].y += (Math.random() - 0.5) * 10

      if (chartData[lastIndex].y < 20) chartData[lastIndex].y = 20
      if (chartData[lastIndex].y > 180) chartData[lastIndex].y = 180

      drawChart()
    }, 2000)
  }

  const cryptoMarketData = [
    {
      symbol: "BTCUSDT",
      name: "Bitcoin / TetherUS",
      price: "109,759.81",
      changePercent: "+0.84%",
      changeValue: "+910.21",
      positive: true,
      icon: "₿",
      iconClass: "btc",
    },
    {
      symbol: "ETHUSDT",
      name: "Ethereum / TetherUS",
      price: "2,593.34",
      changePercent: "+0.89%",
      changeValue: "+22.93",
      positive: true,
      icon: "Ξ",
      iconClass: "eth",
    },
    {
      symbol: "USDT.D",
      name: "Market Cap USDT Dominance, %",
      price: "4.73",
      changePercent: "-0.58%",
      changeValue: "",
      positive: false,
      icon: "₮",
      iconClass: "usdt",
    },
  ]

  const defiMarketData = [
    {
      symbol: "DAI",
      name: "Market Cap DAI, $",
      price: "5.37 B",
      changePercent: "+0.04%",
      changeValue: "+1,985,191.00",
      positive: true,
      icon: "D",
      iconClass: "dai",
    },
    {
      symbol: "UNIUSD",
      name: "Uniswap / U.S. Dollar",
      price: "7.6064311",
      changePercent: "+3.50%",
      changeValue: "",
      positive: true,
      icon: "U",
      iconClass: "uni",
    },
    {
      symbol: "AVAXUSD",
      name: "AVAX / US Dollar",
      price: "18.5970810",
      changePercent: "+0.40%",
      changeValue: "",
      positive: true,
      icon: "A",
      iconClass: "avax",
    },
  ]

  function renderMarketData(data, id) {
    const container = document.getElementById(id)
    if (!container) return

    container.innerHTML = ""

    data.forEach((item) => {
      const marketItem = document.createElement("div")
      marketItem.className = "market-item"
      marketItem.innerHTML = `
        <div class="market-left">
          <div class="market-icon ${item.iconClass}">
            ${item.icon}
          </div>
          <div class="market-info">
            <div class="market-symbol">${item.symbol}</div>
            <div class="market-name">${item.name}</div>
          </div>
        </div>
        <div class="market-right">
          <div class="market-price">${item.price}</div>
          <div class="market-change ${item.positive ? "positive" : "negative"}">
            <span class="change-percent">${item.changePercent}</span>
            ${item.changeValue ? `<span class="change-value">${item.changeValue}</span>` : ""}
          </div>
        </div>
      `
      container.appendChild(marketItem)
    })
  }

  renderMarketData(cryptoMarketData, "crypto-market-list")
  renderMarketData(defiMarketData, "defi-market-list")

  setInterval(() => {
    cryptoMarketData.forEach((item) => {
      const current = Number.parseFloat(item.price.replace(/,/g, ""))
      if (!isNaN(current)) {
        const change = (Math.random() - 0.5) * 0.02
        const newPrice = current * (1 + change)
        item.price = newPrice.toLocaleString("en-US", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      }
    })

    renderMarketData(cryptoMarketData, "crypto-market-list")
    renderMarketData(defiMarketData, "defi-market-list")
  }, 5000)
})

// Mobile ticker animation
function updateMobileTicker() {
  const tickerItems = document.querySelectorAll(".ticker-item")

  if (window.innerWidth <= 767) {
    const mobileData = [
      { symbol: "AAPL", price: "223.41", change: "+3.49 (+1.59%)", positive: true },
      { symbol: "NVDA", price: "159.34", change: "+2.1", positive: true },
    ]

    let index = 0

    setInterval(() => {
      const data = mobileData[index]
      const tickerItem = tickerItems[0]
      if (tickerItem) {
        tickerItem.innerHTML = `
          <span class="ticker-symbol">${data.symbol}</span>
          <span class="ticker-separator">•</span>
          <span class="ticker-price">${data.price}</span>
          <sup class="ticker-sup">D</sup>
          <span class="ticker-change ${data.positive ? "positive" : "negative"}">${data.change}</span>
        `
      }
      index = (index + 1) % mobileData.length
    }, 3000)
  }
}

document.addEventListener("DOMContentLoaded", updateMobileTicker)
window.addEventListener("resize", updateMobileTicker)

// Profile Dropdown
document.addEventListener("DOMContentLoaded", () => {
  const profileToggle = document.getElementById("profileToggle")
  const profileMenu = document.getElementById("profileMenu")

  if (profileToggle && profileMenu) {
    profileToggle.addEventListener("click", (e) => {
      e.stopPropagation()
      profileMenu.classList.toggle("show")
      profileToggle.classList.toggle("active")
    })

    document.addEventListener("click", (e) => {
      if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.classList.remove("show")
        profileToggle.classList.remove("active")
      }
    })
  }

  // Sidebar
  const sidebarToggle = document.getElementById("sidebarToggle")
  const sidebar = document.getElementById("sidebar")
  const sidebarOverlay = document.getElementById("sidebarOverlay")
  const sidebarClose = document.getElementById("sidebarClose")

  // Sidebar dropdown handler (already in your file)
  const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');

  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      const parent = toggle.parentElement;

      document.querySelectorAll('.sidebar-dropdown.open').forEach(openEl => {
        if (openEl !== parent) openEl.classList.remove('open');
      });

      parent.classList.toggle('open');
    });
  });

  // ⭐⭐⭐ Added: Close dropdowns automatically on mobile
  function ensureDropdownsClosedOnMobile() {
    if (window.innerWidth <= 768) {
      document.querySelectorAll('.sidebar-dropdown').forEach(dd => {
        dd.classList.remove('open');
      });
    }
  }

  // Run at startup
  ensureDropdownsClosedOnMobile();

  // Run on resize
  window.addEventListener("resize", ensureDropdownsClosedOnMobile);

  function openSidebar() {
    sidebar.classList.add("show")
    sidebarOverlay.classList.add("show")
    document.body.style.overflow = "hidden"
  }

  function closeSidebar() {
    sidebar.classList.remove("show")
    sidebarOverlay.classList.remove("show")
    document.body.style.overflow = ""
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.contains("show") ? closeSidebar() : openSidebar()
    })
  }

  if (sidebarClose) sidebarClose.addEventListener("click", closeSidebar)
  if (sidebarOverlay) sidebarOverlay.addEventListener("click", closeSidebar)

  const sidebarLinks = document.querySelectorAll(".sidebar-item")
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      if (link.classList.contains('sidebar-dropdown-toggle')) return

      const href = link.getAttribute("href")

      sidebarLinks.forEach((i) => i.classList.remove("active"))
      if (href === window.location.pathname) link.classList.add("active")

      if (window.innerWidth <= 768) closeSidebar()
    })
  })

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && sidebar.classList.contains("show")) {
      closeSidebar()
    }
  })

  const transactionTabs = document.querySelectorAll(".tab-btn")
  const transactionTableHeader = document.getElementById("transaction-table-header")

  if (transactionTabs.length > 0 && transactionTableHeader) {
    updateTransactionTable("deposit")

    transactionTabs.forEach((tab) => {
      tab.addEventListener("click", function () {
        const type = this.dataset.tab
        transactionTabs.forEach((t) => t.classList.remove("active"))
        this.classList.add("active")
        updateTransactionTable(type)
      })
    })
  }

  function updateTransactionTable(type) {
    if (!transactionTableHeader) return

    if (type === "deposit") {
      transactionTableHeader.className = "table-header deposit-headers"
      transactionTableHeader.innerHTML = `
        <div class="table-col">Amount</div>
        <div class="table-col">Payment Mode</div>
        <div class="table-col">Status</div>
        <div class="table-col">Date Created</div>
      `
    } else {
      transactionTableHeader.className = "table-header withdrawal-headers"
      transactionTableHeader.innerHTML = `
        <div class="table-col">Amount Requested</div>
        <div class="table-col">Amount + Charges</div>
        <div class="table-col">Receiving Mode</div>
        <div class="table-col">Status</div>
        <div class="table-col">Date Created</div>
      `
    }
  }

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 768) {
      sidebar.classList.remove("show")
      sidebarOverlay.classList.remove("show")
    }
  })
})

    </script>
</body>
</html>
