<x-admin>
  <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">All NFTs</h2>
      <a href="{{ route('admin.nfts.create') }}">
        <button class="btn btn-primary">Create NFT</button>
      </a>
    </div>

    @if($nfts->isEmpty())
      <div class="text-center py-5">
        <i class="fas fa-image" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
        <p class="text-secondary">No NFTs created yet.</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($nfts as $nft)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card nft-card h-100 shadow-sm border-0">
              <img src="{{ $nft->image_url }}" class="card-img-top nft-image" alt="{{ $nft->name }}">
              <div class="card-body text-center">
                <h5 class="card-title fw-bold">{{ $nft->name }}</h5>
                <p class="text-info small mb-2">Price: {{ number_format($nft->price, 2) }} ETH</p>
                <p class="text-secondary small mb-0">Category: {{ ucfirst($nft->category) }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    .nft-card { background-color: #fff; border-radius: 12px; overflow: hidden; transition: 0.2s; }
    .nft-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
    .nft-image { height: 200px; object-fit: cover; }
  </style>
</x-admin>
