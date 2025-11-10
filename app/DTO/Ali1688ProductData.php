<?php

namespace App\DTO;

class Ali1688ProductData {
    public function __construct(
        public readonly string $externalId,
        public readonly string $title,
        public readonly ?float $priceMin,
        public readonly ?float $priceMax,
        public readonly ?int $moq,
        public readonly ?string $supplierName,
        public readonly ?string $supplierUrl,
        public readonly string $productUrl,
        public readonly ?string $imageUrl,
        public readonly array $images = [],
        public readonly array $raw = []
    ) {}
}
