<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanRun extends Model {
    protected $fillable = [
        'type',
        'marketplace',
        'status',
        'items_found',
        'meta',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'meta'        => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];
}