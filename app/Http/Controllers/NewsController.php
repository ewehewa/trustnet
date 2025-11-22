<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        $response = Http::get('https://min-api.cryptocompare.com/data/v2/news/?lang=EN');
        $news = $response->json()['Data'] ?? [];

        return view('dashboard.user.news', compact('news'));
    }
}
