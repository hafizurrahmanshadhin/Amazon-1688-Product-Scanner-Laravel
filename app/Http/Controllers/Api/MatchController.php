<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductMatch;

class MatchController extends Controller {
    public function index() {
        $q = ProductMatch::with(['amazon','ali1688'])
            ->orderByDesc('similarity')
            ->limit(200)
            ->get();

        return response()->json($q);
    }
}
