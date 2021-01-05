<?php

namespace RankFoundry\SellBriteTile\Commands;

use Illuminate\Console\Command;
use RankFoundry\SellBriteTile\SellBriteStore;
use RankFoundry\SellBriteTile\Services\SellBrite;

class FetchSellBriteErrorsCommand extends Command
{
    protected $signature = 'dashboard:fetch-sellbrite-errors';

    protected $description = 'Fetches products missing key data from SellBrite';

    public function handle()
    {
        $this->info('Fetching SellBrite Product Data...');

        SellBriteStore::make()->setErrors(
                SellBrite::getErrors(
                config('dashboard.tiles.sellbrite.api_token'),
                config('dashboard.tiles.sellbrite.api_key'),
                config('dashboard.tiles.sellbrite.warehouse_uuid'),
            ));

        $this->info('All done!');
    }
}