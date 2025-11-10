<x-dashboard>
  <div class="container py-4">
    <div class="row justify-content-center align-items-center g-4">

      <!-- NFT Image -->
      <div class="col-12 col-md-5 text-center">
        <img src="{{ $nft->image_url }}" 
             alt="{{ $nft->name }}" 
             class="nft-detail-image">
      </div>

      <!-- NFT Details -->
      <div class="col-12 col-md-5 text-white">
        <h3 class="fw-semibold mb-2">{{ $nft->name }}</h3>
        <p class="text-secondary small mb-3">{{ $nft->description }}</p>

        <div class="price-box mb-4">
          <span class="small d-block mb-1">Price</span>
          <h5 class="text-info fw-bold mb-0">{{ number_format($nft->price, 2) }} ETH</h5>
        </div>

        @if ($nft->owner)
          <div class="mb-3">
            <small class="text-secondary">
              Owned by: <span class="text-light fw-semibold">{{ $nft->owner->name ?? 'Unknown' }}</span>
            </small>
          </div>
        @endif

        @php
          $currentUser = auth('web')->user() ?? auth('admin')->user();
        @endphp

        {{-- Only show Buy button if current user does not own it --}}
        @if (!($nft->owner && $nft->owner->id === $currentUser->id && $nft->owner_type === get_class($currentUser)))
          <button id="buyNowBtn" 
                  class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-2"
                  style="background: #6366f1; border: none; border-radius: 8px; font-size: 0.95rem; padding: 10px;">
            <i class="fas fa-shopping-cart"></i> Buy Now
          </button>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal: Insufficient Balance -->
  <div class="modal fade" id="insufficientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white border-0 rounded-3">
        <div class="modal-header border-0">
          <h5 class="modal-title text-warning">Insufficient Balance</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>You don’t have enough balance to buy this NFT.</p>
          <p><strong>Price:</strong> <span id="priceUsd"></span> USD</p>
          <p><strong>Your Balance:</strong> <span id="userBalance"></span> USD</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- FontAwesome + Bootstrap JS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buyBtn = document.getElementById('buyNowBtn');
      if (!buyBtn) return;

      buyBtn.addEventListener('click', () => {
        buyBtn.disabled = true;
        buyBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

        fetch("{{ route('nfts.buy', $nft->id) }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            toastr.success(data.message || 'NFT purchased successfully!');
            setTimeout(() => {
              window.location.href = "{{ route('user.nfts.my') }}";
            }, 1500);
          } else if (data.message && data.message.includes('Insufficient')) {
            // Show insufficient balance modal
            document.getElementById('priceUsd').textContent = data.price_usd || '0';
            document.getElementById('userBalance').textContent = data.user_balance || '0';
            const modal = new bootstrap.Modal(document.getElementById('insufficientModal'));
            modal.show();
          } else {
            toastr.error(data.message || 'Failed to complete purchase.');
          }
        })
        .catch(() => {
          toastr.error('Network error. Please try again.');
        })
        .finally(() => {
          buyBtn.disabled = false;
          buyBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Buy Now';
        });
      });
    });
  </script>

  <style>
    body {
      background-color: #0f172a;
      color: #f9fafb;
    }
    .nft-detail-image {
      width: 100%;
      max-width: 420px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      transition: transform 0.2s ease;
    }
    .nft-detail-image:hover { transform: scale(1.02); }
    .price-box h5 { color: #38bdf8 !important; }
    .modal-content { background-color: #1f2937 !important; }
  </style>
</x-dashboard>
