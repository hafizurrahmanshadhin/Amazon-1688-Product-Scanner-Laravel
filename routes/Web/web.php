<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/login');
