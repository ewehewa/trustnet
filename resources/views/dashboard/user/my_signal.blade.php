<x-dashboard>
  <div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
      <h2 class="page-title mb-0 text-white">My Signal Plans</h2>

      <a href="{{ route('user.signals.index') }}" class="text-decoration-none">
        <button class="btn btn-primary d-flex align-items-center"
                style="background: #4f46e5; border: none; padding: 10px 18px; border-radius: 8px;">
          <i class="fas fa-store me-2"></i> Browse Signals
        </button>
      </a>
    </div>

    @if ($subscriptions->isEmpty())
      <div class="content-card text-center py-5 px-3">
        <i class="fas fa-bell-slash" style="font-size: 48px; color: #6b7280; margin-bottom: 20px;"></i>
        <p class="text-secondary mb-4">You have no active signal subscriptions.</p>
        <a href="{{ route('user.signals.index') }}" class="text-decoration-none">
          <button class="btn btn-outline-light d-flex align-items-center">
            <i class="fas fa-shopping-bag me-2"></i> Subscribe to a Signal
          </button>
        </a>
      </div>

    @else

      <div class="row g-4 justify-content-center">

        @foreach ($subscriptions as $sub)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">

            <div class="card signal-card shadow-sm border-0 h-100">

              <div class="card-body text-white text-center">

                <!-- Title -->
                <h4 class="fw-bold mb-3">{{ $sub->signal->name }}</h4>

                <!-- Price -->
                <p class="price-label mb-1 text-gray-400">
                  <i class="fas fa-tag me-1"></i> Price
                </p>
                <p class="price-value fw-bold mb-3">
                  <i class="fas fa-dollar-sign me-1"></i>
                  {{ number_format($sub->signal->price, 2) }}
                </p>
              </div>

              <!-- Description 1 -->
              <div class="desc-block p-3 text-center">
                <p class="mb-0">
                  <i class="fas fa-info-circle me-1 text-indigo-400"></i>
                  {{ $sub->signal->description_1 }}
                </p>
              </div>

              <!-- Description 2 -->
              @if ($sub->signal->description_2)
                <div class="desc-block p-3 text-center">
                  <p class="mb-0">
                    <i class="fas fa-file-alt me-1 text-indigo-400"></i>
                    {{ $sub->signal->description_2 }}
                  </p>
                </div>
              @endif

              <!-- Duration ONLY -->
              <div class="desc-block p-3 text-center">
                <p class="mb-1">
                  <i class="fas fa-clock me-1 text-blue-400"></i>
                  Duration: {{ $sub->signal->duration }} Weeks
                </p>
              </div>

            </div>

          </div>
        @endforeach

      </div>

    @endif
  </div>

  <!-- Styles -->
  <style>
    .signal-card {
      background-color: #1f2937;
      border-radius: 16px;
      overflow: hidden;
      transition: 0.25s ease;
      border: 1px solid #2d3748;
    }
    .signal-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.35);
    }

    .price-label {
      color: #9ca3af;
      font-size: 0.9rem;
    }
    .price-value {
      color: #22c55e;
      font-size: 1.35rem;
    }

    .desc-block {
      background-color: #111827;
      border-top: 1px solid #374151;
    }
    .desc-block p {
      color: #d1d5db;
      font-size: 0.92rem;
    }
  </style>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

</x-dashboard>
