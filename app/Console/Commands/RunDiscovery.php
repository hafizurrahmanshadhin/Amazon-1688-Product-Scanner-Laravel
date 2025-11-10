<?php

namespace App\Console\Commands;

use App\Jobs\FetchAmazonBestSellers;
use App\Jobs\MatchProducts;
use App\Jobs\SearchAli1688ForAmazonProduct;
use App\Models\AmazonProduct;
use App\Models\ScanRun;
use Illuminate\Console\Command;

class RunDiscovery extends Command {
    protected $signature = 'discovery:run
        {--marketplaces= : Comma-separated marketplaces (default: env AMAZON_MARKETPLACES)}
        {--limit=0 : Limit Amazon products to search on 1688 (0 = all)}';

    protected $description = 'Full pipeline: Amazon -> 1688 -> Match';

    public function handle(): int {
        $marketsOption = $this->option('marketplaces') ?: env('AMAZON_MARKETPLACES', 'US');
        $markets       = array_filter(array_map('trim', explode(',', $marketsOption)));

        foreach ($markets as $m) {
            $run = ScanRun::create([
                'type'        => 'amazon',
                'marketplace' => $m,
                'status'      => 'pending',
            ]);

            // Requires Dispatchable on the Job
            FetchAmazonBestSellers::dispatch($run, $m);
        }

        $this->info('Queued Amazon fetch jobs.');

        $this->callLater((int) $this->option('limit'));

        return 0;
    }

    protected function callLater(int $limit = 0): void {
        dispatch(function () use ($limit) {
            $query = AmazonProduct::query()->orderByDesc('id');
            if ($limit > 0) {
                $query->limit($limit);
            }
            $ids = $query->pluck('id');

            foreach ($ids as $id) {
                SearchAli1688ForAmazonProduct::dispatch($id);
            }

            MatchProducts::dispatch();
        })->delay(now()->addMinutes(2));

        $this->info('Pipeline queued (1688 searches + matching will start shortly).');
    }
}