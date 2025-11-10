<?php

use App\Http\Controllers\ResetController;
use App\Http\Controllers\Web\Frontend\HomeController;
use App\Http\Controllers\Web\Frontend\PageController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;

// Route for Reset Database and Optimize Clear and Cache
Route::get('/reset', [ResetController::class, 'Reset'])->name('reset');
Route::get('/cache', [ResetController::class, 'Cache'])->name('cache');

// Route for Landing Page
Route::get('/', [HomeController::class, 'index'])->name('index');

// Route for Dynamic Pages (Privacy Policy, Terms and Conditions)
Route::get('/page/{type}', [PageController::class, 'dynamicPage'])
    ->whereIn('type', ['privacyPolicy', 'termsAndConditions'])
    ->name('dynamicPage.show');

Route::get('/test-amazon', function () {
    $url  = 'https://www.amazon.com/Best-Sellers/zgbs';
    $html = Http::get($url)->body();

    $crawler = new Crawler($html);

    $products = $crawler->filter('.zg-grid-general-faceout')->each(function ($node) {
        return [
            'name'  => trim($node->filter('.p13n-sc-truncate')->text('')),
            'price' => $node->filter('.p13n-sc-price')->count() ? $node->filter('.p13n-sc-price')->text() : null,
            'link'  => 'https://www.amazon.com' . $node->filter('a.a-link-normal')->attr('href'),
        ];
    });

    return $products;
});
