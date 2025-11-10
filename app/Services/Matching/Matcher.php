<?php

namespace App\Services\Matching;

use App\Models\AmazonProduct;
use App\Models\Ali1688Product;
use Illuminate\Support\Facades\Http;

class Matcher {
    public function score(AmazonProduct $a, Ali1688Product $b): float {
        $clipUrl = config('services.clip.url', env('CLIP_SERVICE_URL'));
        $resp = Http::post($clipUrl, [
            'images' => array_filter([$a->image_url, $b->image_url]),
            'texts'  => [$a->title, $b->title]
        ]);
        if (!$resp->ok()) return 0.0;
        $data = $resp->json();
        // Simplified: cosine sim between first image pair or fallback text
        $imgEmb = $data['image_embeddings'] ?? [];
        if (count($imgEmb) >= 2) {
            $v1 = $imgEmb[0]; $v2 = $imgEmb[1];
            $dot = 0; $n1=0; $n2=0;
            for ($i=0;$i<count($v1);$i++){
                $dot += $v1[$i]*$v2[$i];
                $n1 += $v1[$i]*$v1[$i];
                $n2 += $v2[$i]*$v2[$i];
            }
            return $dot / (sqrt($n1)*sqrt($n2));
        }
        return 0.0;
    }

    public function estimateMargin(?float $amazonPrice, ?float $costMin): ?float {
        if ($amazonPrice === null || $costMin === null) return null;
        // naive margin (subtract 25% fees)
        return ($amazonPrice * 0.75) - $costMin;
    }
}
