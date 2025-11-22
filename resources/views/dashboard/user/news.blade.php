<x-dashboard>
    <div class="trading-section card shadow-sm border-0 p-4 mb-4" style="background:#121212; color:#fff;">
        <h3 class="section-title mb-4">Latest Market News</h3>

        <div class="row">
            @if(!empty($news) && is_array($news))
                @foreach($news as $item)
                    @php
                        $item = is_array($item) ? $item : (array) $item;
                        $title = $item['title'] ?? 'No Title';
                        $text = $item['body'] ?? $item['text'] ?? $item['summary'] ?? '';
                        $url = $item['url'] ?? '#';
                        $image = $item['imageurl'] ?? $item['image'] ?? null;
                        $published = isset($item['published_on'])
                            ? \Carbon\Carbon::createFromTimestamp($item['published_on'])
                            : now();
                    @endphp

                    <div class="col-12 col-lg-4 mb-4">
                        <div class="card news-card h-100 shadow-sm" style="background:#1a1a1a; border-radius:12px; color:#fff;">
                            @if($image)
                                <img src="{{ $image }}" class="card-img-top"
                                     style="border-top-left-radius:12px; border-top-right-radius:12px; height:190px; object-fit:cover;">
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-2">{{ $title }}</h6>

                                <p class="news-preview mb-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($text), 160) }}
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <small class="news-time">
                                        {{ $published->diffForHumans() }}
                                    </small>
                                    <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-primary px-3">Read</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-secondary w-100">No news available.</p>
            @endif
        </div>
    </div>

    <style>
        /* Card and text styling */
        .card-title {
            font-weight: 600;
            font-size: 0.98rem;
            line-height: 1.35rem;
            color: #ffffff;
        }
        .news-preview {
            font-size: 0.9rem;
            line-height: 1.45rem;
            color: #cccccc;
            opacity: 0.9;
            transition: color 0.3s, opacity 0.3s;
        }
        .news-card:hover .news-preview {
            color: #ffffff;
            opacity: 1;
        }
        .news-time {
            color: #aaaaaa;
            font-size: 0.8rem;
        }

        /* Button styling */
        .btn-primary {
            background:#00bcd4;
            border-color:#00bcd4;
            border-radius: 6px;
        }
        .btn-primary:hover {
            background:#0099b0;
            border-color:#0099b0;
        }

        /* Force full width on iPad and mobile screens */
        @media (max-width: 991.98px) { /* Bootstrap lg breakpoint and below */
            .col-lg-4 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</x-dashboard>
