<?php

namespace App\Services\Ali1688;

use App\DTO\Ali1688ProductData;

class Ali1688Scraper {
    public function search(string $translatedQuery, int $limit = 10): array {
        return [
            new Ali1688ProductData(
                externalId: 'mock-' . md5($translatedQuery),
                title: $translatedQuery . ' Supplier Variant',
                priceMin: 2.50,
                priceMax: 3.20,
                moq: 50,
                supplierName: 'Demo Supplier',
                supplierUrl: 'https://1688.com/supplier/demo',
                productUrl: 'https://1688.com/item/demo',
                imageUrl: null,
                images: [],
                raw: []
            ),
        ];
    }
}
