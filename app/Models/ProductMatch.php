<?php

namespace App\Models;

use App\Models\Ali1688Product;
use App\Models\AmazonProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMatch extends Model {
    protected $guarded = [];
    protected $casts   = ['meta' => 'array'];

    public function amazon(): BelongsTo {
        return $this->belongsTo(AmazonProduct::class);
    }
    
    public function ali1688(): BelongsTo {
        return $this->belongsTo(Ali1688Product::class, 'ali1688_product_id');
    }
}
