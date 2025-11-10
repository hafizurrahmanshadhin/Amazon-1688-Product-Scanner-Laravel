<?php

namespace App\DTO;

class BestSellerItem {
    public function __construct(
        public string $asin,
        public string $marketplace,
        public ?string $title = null,
        public ?string $category = null,
        public ?float $price = null,
        public ?float $rating = null,
        public ?int $reviews = null,
        public ?string $imageUrl = null,
        public array $images = [],
        public array $raw = [],
    ) {}
}