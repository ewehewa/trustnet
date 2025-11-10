<x-dashboard>
  <div class="container py-5">

    <h2 class="page-title mb-4">My NFTs</h2>

    @if ($nfts->isEmpty())
      <div class="text-center py-5">
        <i class="fas fa-image" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
        <p class="text-secondary" style="font-size: 16px;">
          You have not purchased or created any NFTs yet.
        </p>
        <a href="{{ route('nfts.mint.form') }}">
          <button class="btn btn-primary mt-3">Create Your First NFT</button>
        </a>
      </div>
    @else
      <div class="row g-4 justify-content-center">
        @foreach ($nfts as $nft)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card nft-card h-100 shadow-sm border-0">
              <img src="{{ $nft->image_url }}" alt="{{ $nft->name }}" class="card-img-top nft-image">
              <div class="card-body text-center text-white d-flex flex-column justify-content-between">
                <h5 class="card-title fw-bold mb-2">{{ $nft->name }}</h5>
                <p class="text-info small mb-2">Price: {{ number_format($nft->price, 2) }} ETH</p>
                <a href="{{ route('nfts.show', $nft->id) }}" class="btn btn-sm btn-outline-light w-100">
                  <i class="fas fa-eye me-1"></i> View
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    .nft-card {
      background-color: #1f2937;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .nft-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
    .nft-image {
      height: 220px;
      object-fit: cover;
    }
    .card-title {
      font-size: 1.1rem;
    }
    .text-info.small {
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .nft-image { height: 180px; }
    }
    @media (max-width: 576px) {
      .nft-card { width: 100%; }
      .nft-image { height: 160px; }
    }
  </style>
</x-dashboard>
