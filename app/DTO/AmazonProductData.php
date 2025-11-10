<?php

namespace App\DTO;

class AmazonProductData {
    public function __construct(
        public readonly string $asin,
        public readonly string $marketplace,
        public readonly string $title,
        public readonly ?string $category,
        public readonly ?float $price,
        public readonly ?float $rating,
        public readonly ?int $reviews,
        public readonly ?string $imageUrl,
        public readonly array $images = [],
        public readonly array $raw = []
    ) {}
}
