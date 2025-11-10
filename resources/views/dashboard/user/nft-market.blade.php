<x-dashboard>
  <div class="container-fluid px-4">

   <!-- Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
  <h2 class="page-title mb-0">NFT Marketplace</h2>

  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('nfts.mint.form') }}" class="text-decoration-none">
      <button class="btn btn-primary d-flex align-items-center" 
              style="background: #6366f1; border: none; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-plus me-2"></i> Create NFT
      </button>
    </a>

    <a href="{{ route('user.nfts.my') }}" class="text-decoration-none">
      <button class="btn btn-secondary d-flex align-items-center" 
              style="background: #4b5563; border: none; padding: 10px 18px; border-radius: 8px;">
        <i class="fas fa-user me-2"></i> My NFTs
      </button>
    </a>
  </div>
</div>


    <!-- Category Tabs -->
    <ul class="nav nav-tabs flex-wrap mb-4" id="nftTabs">
      @php
          $categories = ['all' => 'All', 'collectibles' => 'Collectibles', 'art' => 'Art', 'music' => 'Music', 'photography' => 'Photography'];
      @endphp
      @foreach($categories as $key => $label)
        <li class="nav-item">
          <a class="nav-link {{ $loop->first ? 'active' : '' }}" href="#" data-category="{{ $key }}">{{ $label }}</a>
        </li>
      @endforeach
    </ul>

    <!-- NFT Grid -->
    @if ($nfts->isEmpty())
      <div class="content-card text-center py-5 px-3">
        <i class="fas fa-image" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
        <p class="text-secondary mb-4">No NFTs available at the moment.</p>
        <a href="{{ route('nfts.mint.form') }}">
          <button class="btn btn-outline-light">Mint Your First NFT</button>
        </a>
      </div>
    @else
      <div class="row g-4 justify-content-center nft-container">
        @foreach ($nfts as $nft)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 nft-card-wrapper" data-category="{{ $nft->category }}">
            <div class="card nft-card h-100 shadow-sm border-0">
              <img src="{{ $nft->image_url }}" alt="{{ $nft->name }}" class="card-img-top nft-image">
              <div class="card-body text-center text-white d-flex flex-column justify-content-between">
                <h5 class="card-title fw-bold mb-2">{{ $nft->name }}</h5>
                <div>
                  <p class="text-info small mb-2">Price: {{ number_format($nft->price, 2) }} ETH</p>
                  <a href="{{ route('nfts.show', $nft->id) }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="fas fa-shopping-cart me-1"></i> Buy Now
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <!-- Scripts -->
  <script>
    const tabs = document.querySelectorAll('#nftTabs .nav-link');
    const nftCards = document.querySelectorAll('.nft-card-wrapper');

    tabs.forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();

        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const category = this.dataset.category;

        // Show/hide NFTs based on category
        nftCards.forEach(card => {
          if (category === 'all' || card.dataset.category === category) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  </script>

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    .nft-card { background-color: #1f2937; border-radius: 15px; overflow: hidden; transition: 0.2s; }
    .nft-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.3); }
    .nft-image { height: 220px; object-fit: cover; }
    .card-title { font-size: 1.1rem; }
    .text-info.small { font-size: 0.9rem; }

    @media (max-width: 768px) { .nft-image { height: 180px; } .btn { font-size: 14px; } .page-title { font-size: 1.4rem; } }
    @media (max-width: 576px) { .nft-card { width: 100%; } .nft-image { height: 160px; } .nav-tabs .nav-link { font-size: 14px; padding: 6px 10px; } }
  </style>
</x-dashboard>
