<?php

namespace App\Services\Amazon;

use App\DTO\BestSellerItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AmazonScraper {
    public function fetchBestSellers(string $marketplace, string $url, int $limit = 50): array {
        try {
            $resp = Http::withHeaders($this->headers($marketplace))->timeout(15)->get($url);
            if (!$resp->ok()) {
                Log::warning('AmazonScraper non-200', ['status' => $resp->status()]);
                return $this->fallback($marketplace, $limit);
            }
            $html = $resp->body();
            preg_match_all('/data-asin="([A-Z0-9]{5,20})"/', $html, $m);
            $asins = array_slice(array_unique($m[1] ?? []), 0, $limit);
            if (empty($asins)) {
                Log::info('AmazonScraper zero ASIN -> fallback');
                return $this->fallback($marketplace, $limit);
            }
            return array_map(fn($asin, $i) => new BestSellerItem(
                asin: $asin,
                marketplace: $marketplace,
                title: "Placeholder $asin",
                category: 'Best Sellers',
                price: 19.99 + $i,
                rating: 4.5,
                reviews: 120 + $i,
                imageUrl: 'https://via.placeholder.com/300',
                images: [],
                raw: ['source' => 'html']
            ), $asins, array_keys($asins));
        } catch (Throwable $e) {
            Log::error('AmazonScraper exception', ['error' => $e->getMessage()]);
            return $this->fallback($marketplace, $limit);
        }
    }

    protected function headers(string $marketplace): array {
        return [
            'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/119.0 Safari/537.36',
            'Accept-Language' => match (strtoupper($marketplace)) {
                'DE'    => 'de-DE,de;q=0.9,en;q=0.8',
                'FR'    => 'fr-FR,fr;q=0.9,en;q=0.8',
                'IT'    => 'it-IT,it;q=0.9,en;q=0.8',
                'ES'    => 'es-ES,es;q=0.9,en;q=0.8',
                'UK'    => 'en-GB,en;q=0.9',
                default => 'en-US,en;q=0.9',
            },
        ];
    }

    protected function fallback(string $marketplace, int $limit): array {
        $out = [];
        $n   = min($limit, 10);
        for ($i = 1; $i <= $n; $i++) {
            $asin  = sprintf('FAKE%05d', $i);
            $out[] = new BestSellerItem(
                asin: $asin,
                marketplace: $marketplace,
                title: "Demo Product $i",
                category: 'Demo',
                price: 25 + $i,
                rating: 4.4,
                reviews: 90 + $i,
                imageUrl: 'https://via.placeholder.com/300',
                images: [],
                raw: ['demo' => true]
            );
        }
        return $out;
    }
}