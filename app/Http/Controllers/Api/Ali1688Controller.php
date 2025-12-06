<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Ali1688ProxyController;
use App\Http\Controllers\Controller;
use App\Models\Ali1688Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Ali1688Controller extends Controller {
    /**
     * List stored 1688 products.
     */
    public function index(Request $request): JsonResponse {
        $query = Ali1688Product::query();

        if ($title = $request->query('q')) {
            $query->where('title', 'like', '%' . $title . '%');
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
     * Custom 1688 "API" search endpoint.
     *
     * This should internally call your existing 1688 proxy / scraper
     * (Ali1688ProxyController) and optionally persist results.
     */
    public function search(Request $request): JsonResponse {
        if (!$request->input('q')) {
            return response()->json([
                'success' => false,
                'message' => 'Missing "q" parameter.',
            ], 422);
        }
        return app(Ali1688ProxyController::class)->search($request);
    }
}
