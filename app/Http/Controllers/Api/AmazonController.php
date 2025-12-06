<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AmazonProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AmazonController extends Controller {
    /**
     * List stored Amazon best-seller products (with basic filters).
     */
    public function index(Request $request): JsonResponse {
        $query = AmazonProduct::query();

        if ($marketplace = $request->query('marketplace')) {
            $query->where('marketplace', $marketplace);
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        $products = $query
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 25));

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    /**
     * Trigger a new scan for Amazon best-sellers.
     *
     * NOTE: This is a thin wrapper – inside you should call
     *       your existing service/command that already populates
     *       the `amazon_products` table.
     */
    public function scan(Request $request): JsonResponse {
        $marketplace = $request->input('marketplace', 'US');
        $rootUrl     = $request->input('bestseller_url');

        Artisan::call('amazon:fetch-bestsellers', [
            '--marketplace' => $marketplace,
            '--url'         => $rootUrl,
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'Best-seller scan started.',
            'marketplace'    => $marketplace,
            'bestseller_url' => $rootUrl,
        ]);
    }
}
