<x-dashboard>
  <div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
      <h3 class="page-title mb-0 text-white">Signal Plans</h2>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs flex-wrap mb-4" id="signalTabs">
      <li class="nav-item">
        <a class="nav-link active" href="#" data-category="all">All Plans</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.signals.myPlans') }}">My Active Plans</a>
      </li>
    </ul>

    @if ($signals->isEmpty())
      <div class="content-card text-center py-5 px-3">
        <i class="fas fa-satellite-dish" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
        <p class="text-secondary mb-4">No signal plans available right now.</p>
      </div>
    @else

      <!-- Grid -->
      <div class="row g-4 justify-content-center">

        @foreach ($signals as $signal)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">

            <div class="card signal-card h-100 shadow border-0">

              <!-- Header -->
              <div class="card-body text-center text-white">
                <h4 class="fw-bold mb-1">{{ $signal->name }}</h4>

                <p class="price-label mt-2 mb-1">Price</p>
                <p class="price-value fw-bold mb-3">${{ number_format($signal->price, 2) }}</p>
              </div>

              <!-- Description blocks -->
              <div class="desc-block p-3 text-center">
                <p class="mb-0">{{ $signal->description_1 }}</p>
              </div>

              @if ($signal->description_2)
                <div class="desc-block p-3 text-center">
                  <p class="mb-0">{{ $signal->description_2 }}</p>
                </div>
              @endif

              <div class="desc-block p-3 text-center">
                <p class="mb-0">Duration: {{ $signal->duration }} Weeks</p>
              </div>

              <!-- Button -->
              <div class="p-3">
                <button class="btn btn-primary w-100 subscribe-btn"
                        data-id="{{ $signal->id }}">
                  <span class="btn-text">Subscribe Now</span>
                  <span class="spinner-border spinner-border-sm hidden"></span>
                </button>
              </div>

            </div>

          </div>
        @endforeach

      </div>

    @endif
  </div>

  <!-- Script -->
  <script>
    document.querySelectorAll('.subscribe-btn').forEach(btn => {
      btn.addEventListener('click', async function () {
        const id = this.dataset.id;
        const btnText = this.querySelector('.btn-text');
        const spinner = this.querySelector('.spinner-border');

        this.disabled = true;
        btnText.textContent = "Processing...";
        spinner.classList.remove('hidden');

        try {
          const res = await fetch(`/dashboard/subscribe-signal/${id}`, {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
              "Accept": "application/json"
            }
          });

          const data = await res.json();

          if (data.success) {
            toastr.success(data.message);
            setTimeout(() => location.href = "/dashboard/my-signal-plans", 1000);
          } else {
            toastr.error(data.message || "Failed to subscribe");
          }

        } catch (e) {
          toastr.error("Server error");
        }

        this.disabled = false;
        btnText.textContent = "Subscribe Now";
        spinner.classList.add('hidden');
      });
    });
  </script>

  <!-- Styling -->
  <style>
    .signal-card {
      background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.25s ease;
      border: 1px solid #2d3748;
    }

    .signal-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.35);
      border-color: #4b5563;
    }

    .price-label {
      color: #9ca3af;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }

    .price-value {
      color: #6ee7b7;
      font-size: 1.5rem;
    }

    .desc-block {
      background-color: #0f172a;
      border-top: 1px solid #1f2937;
      border-bottom: 1px solid #1f2937;
    }

    .desc-block p {
      color: #d1d5db;
      font-size: 0.95rem;
      line-height: 1.4rem;
    }

    .btn-primary {
      background-color: #6366f1;
      border: none;
      padding: 10px;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      transition: 0.2s;
    }

    .btn-primary:hover {
      background-color: #4f46e5;
      transform: scale(1.02);
    }

    .hidden {
      display: none !important;
    }
  </style>

</x-dashboard>
