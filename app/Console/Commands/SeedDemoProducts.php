<?php

namespace App\Console\Commands;

use App\Models\Ali1688Product;
use App\Models\AmazonProduct;
use Illuminate\Console\Command;

class SeedDemoProducts extends Command {
    protected $signature   = 'demo:seed-products';
    protected $description = 'Seed demo Amazon and 1688 products';

    public function handle(): int {
        $a = AmazonProduct::create([
            'asin'        => 'B00DEMO123',
            'marketplace' => 'US',
            'title'       => 'Demo Red Shoe',
            'category'    => 'Shoes',
            'price'       => 49.99,
            'image_url'   => 'https://via.placeholder.com/300',
            'images'      => [],
            'raw'         => [],
        ]);

        $b = Ali1688Product::create([
            'external_id'   => '1688-DEMO-1',
            'title'         => 'Factory Red Shoe',
            'price_min'     => 12.0,
            'price_max'     => 15.0,
            'moq'           => '2',
            'supplier_name' => 'Demo Supplier',
            'supplier_url'  => 'https://example.com',
            'product_url'   => 'https://example.com/product',
            'image_url'     => 'https://via.placeholder.com/300',
            'images'        => [],
            'raw'           => [],
        ]);

        $this->info("Seeded Amazon #{$a->id} and 1688 #{$b->id}");
        return 0;
    }
}