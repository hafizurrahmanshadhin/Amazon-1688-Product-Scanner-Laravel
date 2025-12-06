<?php

namespace App\Services\Ali1688;

use App\DTO\Ali1688ProductData;
use Illuminate\Support\Facades\Http;

class Ali1688Scraper {
    /**
     * Search 1688 products for a given query.
     *
     * NOTE: This expects an HTTP JSON API at ALI1688_API_ENDPOINT that
     * actually scrapes 1688 and returns:
     *
     * {
     *   "items": [
     *     {
     *       "id": "123",
     *       "title": "...",
     *       "price_min": 2.5,
     *       "price_max": 3.2,
     *       "moq": 50,
     *       "supplier_name": "...",
     *       "supplier_url": "...",
     *       "product_url": "...",
     *       "image_url": "...",
     *       "images": ["...", "..."]
     *     }
     *   ]
     * }
     */
    public function search(string $translatedQuery, int $limit = 10): array {
        $endpoint = config('services.ali1688.endpoint', env('ALI1688_API_ENDPOINT'));

        if (!$endpoint) {
            return [];
        }

        $response = Http::get($endpoint, [
            'q'     => $translatedQuery,
            'limit' => $limit,
        ]);

        if (!$response->ok()) {
            return [];
        }

        $items = $response->json('items') ?? [];

        $results = [];

        foreach ($items as $item) {
            $results[] = new Ali1688ProductData(
                externalId: $item['id'] ?? null,
                title: $item['title'] ?? '',
                priceMin: isset($item['price_min']) ? (float) $item['price_min'] : null,
                priceMax: isset($item['price_max']) ? (float) $item['price_max'] : null,
                moq: isset($item['moq']) ? (int) $item['moq'] : null,
                supplierName: $item['supplier_name'] ?? null,
                supplierUrl: $item['supplier_url'] ?? null,
                productUrl: $item['product_url'] ?? null,
                imageUrl: $item['image_url'] ?? null,
                images: $item['images'] ?? [],
                raw: $item,
            );

            if (count($results) >= $limit) {
                break;
            }
        }

        return $results;
    }
}
