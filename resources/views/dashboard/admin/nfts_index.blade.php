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
          <div class="col-12 col-sm-6 col-md-4 col-lg-3" id="nft-{{ $nft->id }}">
            <div class="card nft-card h-100 shadow-sm border-0">
              <img src="{{ $nft->image_url }}" class="card-img-top nft-image" alt="{{ $nft->name }}">
              <div class="card-body text-center">
                <h5 class="card-title fw-bold">{{ $nft->name }}</h5>
                <p class="text-info small mb-2">Price: {{ number_format($nft->price, 2) }} ETH</p>
                <p class="text-secondary small mb-2">Category: {{ ucfirst($nft->category) }}</p>

                <div class="d-flex justify-content-center gap-2 mt-2">
                  <!-- Edit Button -->
                  <a href="{{ route('admin.nfts.edit', $nft->id) }}" class="btn btn-sm btn-warning">Edit</a>

                  <!-- Delete Button -->
                  <button class="btn btn-sm btn-danger" onclick="deleteNft({{ $nft->id }})">
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .nft-card { background-color: #fff; border-radius: 12px; overflow: hidden; transition: 0.2s; }
    .nft-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
    .nft-image { height: 200px; object-fit: cover; }
  </style>

  <script>
    function deleteNft(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "This NFT will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/admin/nfts/${id}/delete`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              document.getElementById(`nft-${id}`).remove();
              toastr.success(data.message);
            } else {
              toastr.error(data.message || 'Unable to delete.');
            }
          })
          .catch(() => toastr.error('Something went wrong.'));
        }
      });
    }
  </script>
</x-admin>
