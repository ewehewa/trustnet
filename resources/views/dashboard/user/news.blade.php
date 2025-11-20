<x-dashboard>
    <div class="trading-section card shadow-sm border-0 p-4 mb-4" style="background:#121212; color:#fff;">
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
            @if(!empty($news) && is_array($news))
                @foreach($news as $item)
                    @php
                        // Normalize item
                        $item = is_array($item) ? $item : (array) $item;
                        $title = $item['title'] ?? 'No Title';
                        $text = $item['text'] ?? $item['summary'] ?? '';
                        $url = $item['url'] ?? '#';
                        $image = $item['image'] ?? null;
                        $published = $item['publishedDate'] ?? $item['published_at'] ?? now();
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm" style="background:#1a1a1a; border-radius:12px; color:#fff;">
                            @if($image)
                                <img src="{{ $image }}" class="card-img-top" style="border-top-left-radius:12px; border-top-right-radius:12px;" alt="News Image">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $title }}</h6>
                                <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($text, 120) }}</p>
                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary mt-auto">Read More</a>
                            </div>
                            <div class="card-footer text-muted small">
                                {{ \Carbon\Carbon::parse($published)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-secondary w-100">No news available for this category.</p>
            @endif
        </div>
    </div>

    <style>
        .card-title { font-weight:600; font-size:0.95rem; }
        .card-text { font-size:0.85rem; color:#b0b0b0; }
        .nav-tabs .nav-link.active { background-color:#00bcd4; color:#fff; font-weight:600; }
        .card-footer { color:#888; background:#1a1a1a; border-top:none; }
        .btn-primary { background:#00bcd4; border-color:#00bcd4; }
    </style>
</x-dashboard>
