<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Get selected category from query, default is 'all'
        $category = $request->query('category', 'all');

        // FMP News API endpoint
        $endpoint = 'https://financialmodelingprep.com/api/v3/stock_news';
        $limit = 20; // number of articles to fetch

        // Fetch news from FMP
        $response = Http::get($endpoint, [
            'limit' => $limit,
            'apikey' => env('FMP_API_KEY'),
        ]);

        $newsData = [];

        if ($response->successful()) {
            $newsData = $response->json(); // FMP returns an array of news
        }

        // Optional: filter news by category
        if ($category != 'all') {
            $newsData = array_filter($newsData, function ($item) use ($category) {
                $text = strtolower($item['text'] ?? '');
                $site = strtolower($item['site'] ?? '');
                return str_contains($text, $category) || str_contains($site, $category);
            });
        }

        // Pass data to Blade view
        return view('dashboard.user.news', [
            'news' => $newsData,
            'category' => $category,
        ]);
    }
}
