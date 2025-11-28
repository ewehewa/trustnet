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
