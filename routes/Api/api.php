<?php

use App\Http\Controllers\Api\ContentController;
use Illuminate\Support\Facades\Route;

// This route is for getting terms and conditions and privacy policy.
Route::get('contents', [ContentController::class, 'index'])->middleware(['throttle:10,1']);
