<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmazonProduct;
use Illuminate\Http\Request;

class AmazonProductController extends Controller {
    // GET /api/amazon-products
    public function index(Request $request) {
        $q = AmazonProduct::query();

        if ($request->filled('marketplace')) {
            $q->where('marketplace', $request->query('marketplace'));
        }
        if ($request->filled('asin')) {
            $q->where('asin', $request->query('asin'));
        }
        if ($request->filled('q')) {
            $term = $request->query('q');
            $q->where('title', 'like', "%{$term}%");
        }
        if ($request->filled('min_price')) {
            $q->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $q->where('price', '<=', (float) $request->query('max_price'));
        }

        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(1, min($perPage, 100));

        return response()->json(
            $q->orderByDesc('created_at')->paginate($perPage)
        );
    }

    // GET /api/amazon-products/{asin}
    public function show(string $asin) {
        $p = AmazonProduct::where('asin', $asin)->firstOrFail();
        return response()->json($p);
    }
}