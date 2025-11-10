<x-dashboard>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">

        <div class="card shadow-sm border-0 p-4" style="background-color: #1f2937;">
          <h2 class="fw-bold mb-4 text-center text-white">Mint Your NFT</h2>

          <form id="nftMintForm" action="{{ route('user.nfts.mint') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label text-white">NFT Name</label>
              <input type="text" name="name" id="name" class="form-control bg-dark text-white border-0" placeholder="Enter NFT title" required>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label text-white">Description</label>
              <textarea name="description" id="description" class="form-control bg-dark text-white border-0" rows="3" placeholder="Enter description"></textarea>
            </div>

            <div class="mb-3">
              <label for="price" class="form-label text-white">Price (ETH)</label>
              <input type="number" name="price" id="price" class="form-control bg-dark text-white border-0" min="1" step="0.01" placeholder="Enter price" required>
            </div>

            <div class="mb-3">
              <label for="category" class="form-label text-white">Category</label>
              <select name="category" id="category" class="form-select bg-dark text-white border-0" required>
                <option value="">Select category</option>
                <option value="art">Art</option>
                <option value="collectibles">Collectibles</option>
                <option value="music">Music</option>
                <option value="photography">Photography</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="image_file" class="form-label text-white">NFT Image</label>
              <input type="file" name="image_file" id="image_file" class="form-control bg-dark text-white border-0" accept="image/*" required>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg" id="mintButton">
                <span id="mintText"><i class="fas fa-plus me-2"></i> Mint NFT</span>
                <span id="mintLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal for insufficient balance -->
  <div class="modal fade" id="balanceModal" tabindex="-1" aria-labelledby="balanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="balanceModalLabel">Insufficient Balance</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          You need at least $50 to mint an NFT. Please add funds to your wallet.
        </div>
        <div class="modal-footer border-0">
          <a href="{{ route('show.deposit') }}" class="btn btn-primary">Add Funds</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const form = document.getElementById('nftMintForm');
    const balanceModal = new bootstrap.Modal(document.getElementById('balanceModal'));
    const mintButton = document.getElementById('mintButton');
    const mintText = document.getElementById('mintText');
    const mintLoader = document.getElementById('mintLoader');

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      // Show loader
      mintText.classList.add('d-none');
      mintLoader.classList.remove('d-none');
      mintButton.disabled = true;

      const formData = new FormData(form);

      try {
        const response = await axios.post(form.action, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (response.data.success) {
          // Hide loader
          mintLoader.classList.add('d-none');
          mintText.classList.remove('d-none');

          // Show success toast
          const toast = document.createElement('div');
          toast.className = 'toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3';
          toast.role = 'alert';
          toast.innerHTML = `
            <div class="d-flex">
              <div class="toast-body">
                ${response.data.message}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          `;
          document.body.appendChild(toast);
          const bootstrapToast = new bootstrap.Toast(toast, { delay: 2000 });
          bootstrapToast.show();

          // Redirect after toast disappears
          setTimeout(() => {
            window.location.href = "{{ route('nfts.marketplace') }}";
          }, 2000);
        }
      } catch (error) {
        mintLoader.classList.add('d-none');
        mintText.classList.remove('d-none');
        mintButton.disabled = false;

        if (error.response && error.response.data.message.includes('Insufficient balance')) {
          balanceModal.show();
        } else {
          alert(error.response?.data?.message || 'Something went wrong. Please try again.');
        }
      }
    });
  </script>

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    .form-control:focus, .form-select:focus {
      box-shadow: none;
      border-color: #6366f1;
    }
    .form-select {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }
    .btn-primary {
      background-color: #6366f1;
      border-color: #6366f1;
    }
  </style>
</x-dashboard>
