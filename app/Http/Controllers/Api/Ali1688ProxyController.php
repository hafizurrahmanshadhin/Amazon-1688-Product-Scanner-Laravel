<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Ali1688ProxyController extends Controller {
    /**
     * TEMP mock implementation.
     * Later you will replace this with real 1688 scraping/API.
     */
    public function search(Request $request) {
        $query = $request->query('q', '');
        $limit = (int) $request->query('limit', 10);

        $items = [];

        for ($i = 1; $i <= $limit; $i++) {
            $items[] = [
                'id'            => Str::slug($query) . '-' . $i,
                'title'         => trim($query . ' Supplier Variant ' . $i),
                'price_min'     => 2.0 + $i * 0.1,
                'price_max'     => 3.0 + $i * 0.1,
                'moq'           => 50,
                'supplier_name' => 'Demo Supplier ' . $i,
                'supplier_url'  => 'https://1688.com/supplier/demo-' . $i,
                'product_url'   => 'https://1688.com/item/demo-' . $i,
                'image_url'     => null,
                'images'        => [],
            ];
        }

        return response()->json([
            'items' => $items,
        ]);
    }
}
