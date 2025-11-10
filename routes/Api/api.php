<?php

use App\Http\Controllers\Api\AmazonProductController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\MatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// This route is for getting terms and conditions and privacy policy.
Route::get('contents', [ContentController::class, 'index'])->middleware(['throttle:10,1']);

Route::get('/matches', [MatchController::class, 'index']);

Route::post('/clip/test', function (Request $request) {
    // Debug (remove after verification)
    if ($request->query('debug') === '1') {
        return response()->json([
            'raw'          => $request->getContent(),
            'headers'      => $request->headers->all(),
            'texts_input'  => $request->input('texts'),
            'json_decoded' => json_decode($request->getContent(), true),
        ]);
    }

    $payload = [
        'texts'  => (array) $request->input('texts', []),
        'images' => (array) $request->input('images', []),
    ];
    $resp = Http::asJson()->acceptJson()->timeout(30)->post(env('CLIP_SERVICE_URL'), $payload);

    return response()->json([
        'sent'   => $payload,
        'status' => $resp->status(),
        'ok'     => $resp->ok(),
        'data'   => $resp->json(),
    ]);
});

// Amazon products API (controller-based)
Route::get('/amazon-products', [AmazonProductController::class, 'index']);
Route::get('/amazon-products/{asin}', [AmazonProductController::class, 'show']);
