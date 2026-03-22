<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        try {
            $response = Http::get('https://cointelegraph.com/rss');
            if ($response->successful()) {
                $xmlString = $response->body();
                if (!empty($xmlString)) {
                    $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
                    if ($xml !== false) {
                        $news = [];
                        foreach ($xml->channel->item as $item) {
                            $image = null;
                            if (isset($item->enclosure) && isset($item->enclosure['url'])) {
                                $image = (string) $item->enclosure['url'];
                            }
                            
                            $news[] = [
                                'title' => (string) $item->title,
                                'body' => (string) $item->description,
                                'url' => (string) $item->guid ?: (string) $item->link,
                                'imageurl' => $image,
                                'published_on' => strtotime((string) $item->pubDate) ?: time(),
                            ];
                        }
                        return view('dashboard.user.news', compact('news'));
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error if needed, fallback to empty array
        }

        $news = [];
        return view('dashboard.user.news', compact('news'));
    }

}
