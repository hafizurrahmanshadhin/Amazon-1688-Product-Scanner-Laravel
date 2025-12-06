<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ApiProductMatchController extends Controller {
    /**
     * List product matches with relations (API view).
     */
    public function index(Request $request): JsonResponse {
        $query = ProductMatch::with(['amazon', 'ali1688'])
            ->orderByDesc('similarity');

        if ($marketplace = $request->query('marketplace')) {
            $query->whereHas('amazon', function ($q) use ($marketplace) {
                $q->where('marketplace', $marketplace);
            });
        }

        $matches = $query->paginate($request->integer('per_page', 25));

        return response()->json([
            'success' => true,
            'data'    => $matches,
        ]);
    }

    /**
     * Run the full matching pipeline.
     *
     * Internally this should call your existing job / command that:
     * - picks Amazon products
     * - fetches 1688 candidates
     * - calls CLIP service
     * - writes to product_matches table
     */
    public function run(Request $request): JsonResponse {
        Artisan::call('matches:build');

        return response()->json([
            'success' => true,
            'message' => 'Matching pipeline started.',
        ]);
    }
}
