<?php

namespace App\Services\Matching;

use App\Models\Ali1688Product;
use App\Models\AmazonProduct;
use Illuminate\Support\Facades\Http;

class Matcher {
    public function score(AmazonProduct $a, Ali1688Product $b): float {
        $clipUrl = config('services.clip.url', env('CLIP_SERVICE_URL', 'http://127.0.0.1:5055/embed'));

        $images = array_filter([$a->image_url, $b->image_url]);
        $texts  = [$a->title ?? '', $b->title ?? ''];

        $resp = Http::post($clipUrl, [
            'images' => $images,
            'texts'  => $texts,
        ]);

        if (!$resp->ok()) {
            return $this->fallbackScore($a->title ?? '', $b->title ?? '');
        }

        $data   = $resp->json();
        $imgEmb = $data['image_embeddings'] ?? [];
        $txtEmb = $data['text_embeddings'] ?? [];

        // 1) Prefer image embeddings if both available
        if (count($imgEmb) >= 2) {
            return $this->cosine($imgEmb[0], $imgEmb[1]);
        }

        // 2) Fallback to text embeddings
        if (count($txtEmb) >= 2) {
            return $this->cosine($txtEmb[0], $txtEmb[1]);
        }

        // 3) Last fallback – simple text similarity on titles
        return $this->fallbackScore($a->title ?? '', $b->title ?? '');
    }

    protected function cosine(array $v1, array $v2): float {
        $dot = 0.0;
        $n1  = 0.0;
        $n2  = 0.0;
        $len = min(count($v1), count($v2));

        for ($i = 0; $i < $len; $i++) {
            $dot += $v1[$i] * $v2[$i];
            $n1 += $v1[$i] * $v1[$i];
            $n2 += $v2[$i] * $v2[$i];
        }

        if ($n1 <= 0 || $n2 <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($n1) * sqrt($n2));
    }

    protected function fallbackScore(string $t1, string $t2): float {
        $norm = function (string $s): array {
            $s      = mb_strtolower($s);
            $s      = preg_replace('/[^a-z0-9 ]+/u', ' ', $s);
            $tokens = preg_split('/\s+/', trim($s)) ?: [];
            return array_values(array_filter($tokens));
        };

        $a = array_unique($norm($t1));
        $b = array_unique($norm($t2));

        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $inter = array_intersect($a, $b);
        $union = array_unique(array_merge($a, $b));

        return count($union) ? count($inter) / count($union) : 0.0;
    }

    public function estimateMargin(?float $amazonPrice, ?float $costMin): ?float {
        if ($amazonPrice === null || $costMin === null) {
            return null;
        }

        // Very rough heuristic – 25% taken as fees/shipping
        return ($amazonPrice * 0.75) - $costMin;
    }
}
