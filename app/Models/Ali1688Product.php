<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ali1688Product extends Model {
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'raw'    => 'array',
    ];
}
