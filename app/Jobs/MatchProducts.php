<?php

namespace App\Jobs;

use App\Models\Ali1688Product;
use App\Models\AmazonProduct;
use App\Models\ProductMatch;
use App\Services\Matching\Matcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MatchProducts implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(Matcher $matcher): void {
        $threshold     = (float) env('MATCH_MIN_SIMILARITY', 0.35);
        $maxCandidates = (int) env('MATCH_MAX_CANDIDATES', 10);

        $amazon = AmazonProduct::latest()->take(200)->get();
        $ali    = Ali1688Product::latest()->take(500)->get();

        $created = 0;

        foreach ($amazon as $a) {
            // keep top matches per Amazon product
            $candidates = [];

            foreach ($ali as $b) {
                $score = $matcher->score($a, $b);

                // Fallback if CLIP/score failed or is too low
                if (!is_numeric($score) || $score <= 0) {
                    $score = $this->fallbackScore((string) $a->title, (string) $b->title);
                }

                if ($score < $threshold) {
                    continue;
                }

                $candidates[] = [
                    'b'     => $b,
                    'score' => $score,
                ];
            }

            // Keep top-N by score
            usort($candidates, fn($x, $y) => $y['score'] <=> $x['score']);
            $candidates = array_slice($candidates, 0, $maxCandidates);

            foreach ($candidates as $row) {
                $b      = $row['b'];
                $score  = $row['score'];
                $margin = $matcher->estimateMargin($a->price, $b->price_min);

                ProductMatch::updateOrCreate(
                    ['amazon_product_id' => $a->id, 'ali1688_product_id' => $b->id],
                    [
                        'similarity'        => $score,
                        'already_on_amazon' => $this->alreadyOnAmazon($a, $b),
                        'amazon_price'      => $a->price,
                        'cost_price'        => $b->price_min,
                        'estimated_margin'  => $margin,
                        'meta'              => ['source' => 'auto'],
                    ]
                );
                $created++;
            }

            Log::info('MatchProducts candidates', [
                'amazon_id' => $a->id,
                'asin'      => $a->asin,
                'title'     => $a->title,
                'kept'      => count($candidates),
            ]);
        }

        Log::info('MatchProducts done', ['created_or_updated' => $created, 'threshold' => $threshold, 'maxCandidates' => $maxCandidates]);
    }

    protected function alreadyOnAmazon($a, $b): bool {
        similar_text(mb_strtolower((string) $a->title), mb_strtolower((string) $b->title), $pct);
        return $pct > 82;
    }

    // Very simple token Jaccard similarity as a fallback
    protected function fallbackScore(string $t1, string $t2): float {
        $norm = fn($s) => preg_split('/\s+/', trim(preg_replace('/[^a-z0-9 ]+/i', ' ', mb_strtolower($s)))) ?: [];
        $a    = array_values(array_filter($norm($t1)));
        $b    = array_values(array_filter($norm($t2)));
        if (!$a || !$b) {
            return 0.0;
        }

        $sa    = array_unique($a);
        $sb    = array_unique($b);
        $inter = array_intersect($sa, $sb);
        $union = array_unique(array_merge($sa, $sb));
        return count($union) ? count($inter) / count($union) : 0.0;
    }
}