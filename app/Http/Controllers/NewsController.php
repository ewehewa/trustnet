<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all'); // all, finance, forex, stock, crypto
        $apiKey = config('services.fmp.key');

        $endpoint = match($category) {
            'finance' => "https://financialmodelingprep.com/api/v3/stock_news?limit=50&apikey={$apiKey}",
            'forex' => "https://financialmodelingprep.com/api/v3/forex_news?limit=50&apikey={$apiKey}",
            'crypto' => "https://financialmodelingprep.com/api/v3/cryptocurrency_news?limit=50&apikey={$apiKey}",
            default => "https://financialmodelingprep.com/api/v3/stock_news?limit=50&apikey={$apiKey}",
        };

        $response = Http::get($endpoint);
        $news = $response->json();

        return view('dashboard.user.news', compact('news', 'category'));
    }
}
