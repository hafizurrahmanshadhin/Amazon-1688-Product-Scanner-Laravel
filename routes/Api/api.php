<?php

use App\Http\Controllers\Api\Ali1688Controller;
use App\Http\Controllers\Api\Ali1688ProxyController;
use App\Http\Controllers\Api\AmazonController;
use App\Http\Controllers\Api\AmazonProductController;
use App\Http\Controllers\Api\ApiProductMatchController;
use App\Http\Controllers\Api\MatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::get('/internal/ali1688/search', [Ali1688ProxyController::class, 'search'])->name('internal.ali1688.search');

Route::prefix('amazon')->group(function () {
    Route::get('best-sellers', [AmazonController::class, 'index']);
    Route::post('best-sellers/scan', [AmazonController::class, 'scan']);
});

Route::prefix('ali1688')->group(function () {
    Route::get('products', [Ali1688Controller::class, 'index']);
    Route::post('search', [Ali1688Controller::class, 'search']);
});

Route::prefix('matches')->group(function () {
    Route::get('/', [ApiProductMatchController::class, 'index']);
    Route::post('run', [ApiProductMatchController::class, 'run']);
});
