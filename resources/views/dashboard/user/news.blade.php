<x-dashboard>
    <div class="trading-section card shadow-sm border-0 p-4 mb-4">
        <h3 class="section-title mb-3">Market News</h3>

        <!-- News Category Tabs -->
        <ul class="nav nav-tabs mb-4">
            @foreach(['all' => 'All', 'finance' => 'Finance', 'forex' => 'Forex', 'crypto' => 'Crypto'] as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $category == $key ? 'active' : '' }}" 
                       href="{{ route('dashboard.news', ['category' => $key]) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="row">
            @forelse($news as $item)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm" style="background:#1a2533; border-radius:12px;">
                        @if(isset($item['image']) && $item['image'])
                            <img src="{{ $item['image'] }}" class="card-img-top" style="border-top-left-radius:12px; border-top-right-radius:12px;" alt="News Image">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-white">{{ $item['title'] }}</h6>
                            <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($item['text'] ?? $item['summary'] ?? '', 120) }}</p>
                            <a href="{{ $item['url'] ?? '#' }}" target="_blank" class="btn btn-sm btn-primary mt-auto">Read More</a>
                        </div>
                        <div class="card-footer text-muted small">
                            {{ \Carbon\Carbon::parse($item['publishedDate'] ?? $item['published_at'] ?? now())->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-secondary">No news available for this category.</p>
            @endforelse
        </div>
    </div>

    <style>
        .card-title { font-weight:600; font-size:0.95rem; }
        .card-text { font-size:0.85rem; }
        .nav-tabs .nav-link.active { background-color:#00bcd4; color:#fff; font-weight:600; }
    </style>
</x-dashboard>
