<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmazonProduct extends Model {
    protected $guarded = [];

    protected $casts   = [
        'images' => 'array',
        'raw'    => 'array',
    ];
}
