<?php

namespace App\Jobs;

use App\Models\Ali1688Product;
use App\Models\AmazonProduct;
use App\Services\Ali1688\Ali1688Scraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchAli1688ForAmazonProduct implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $amazonProductId) {}

    public function handle(Ali1688Scraper $scraper): void {
        $product = AmazonProduct::find($this->amazonProductId);
        if (!$product) {
            return;
        }

        $query   = $this->translate($product->title);
        $results = $scraper->search($query, 10);

        foreach ($results as $dto) {
            Ali1688Product::updateOrCreate(
                ['external_id' => $dto->externalId],
                [
                    'title'         => $dto->title,
                    'price_min'     => $dto->priceMin,
                    'price_max'     => $dto->priceMax,
                    'moq'           => $dto->moq,
                    'supplier_name' => $dto->supplierName,
                    'supplier_url'  => $dto->supplierUrl,
                    'product_url'   => $dto->productUrl,
                    'image_url'     => $dto->imageUrl,
                    'images'        => $dto->images,
                    'raw'           => $dto->raw,
                ]
            );
        }
    }

    protected function translate(string $text): string {
        return $text; // placeholder
    }
}