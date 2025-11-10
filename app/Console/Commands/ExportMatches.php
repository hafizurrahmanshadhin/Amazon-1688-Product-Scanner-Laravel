<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductMatch;
use League\Csv\Writer;
use SplTempFileObject;

class ExportMatches extends Command {
    protected $signature = 'matches:export {path=storage/app/matches.csv}';
    protected $description = 'Export matches to CSV';

    public function handle(): int {
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne(['similarity','amazon_asin','marketplace','amazon_title','amazon_price','1688_title','1688_min_price','margin','already_on_amazon']);
        ProductMatch::with(['amazon','ali1688'])->orderByDesc('similarity')->chunk(500,function($chunk) use ($csv){
            foreach ($chunk as $m) {
                $csv->insertOne([
                    $m->similarity,
                    $m->amazon->asin,
                    $m->amazon->marketplace,
                    $m->amazon->title,
                    $m->amazon_price,
                    $m->ali1688->title,
                    $m->cost_price,
                    $m->estimated_margin,
                    $m->already_on_amazon ? 1 : 0
                ]);
            }
        });
        file_put_contents($this->argument('path'), (string) $csv);
        $this->info('Exported to '.$this->argument('path'));
        return 0;
    }
}
