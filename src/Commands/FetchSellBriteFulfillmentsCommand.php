<?php

namespace RankFoundry\SellBriteTile\Commands;

use Illuminate\Console\Command;
use RankFoundry\SellBriteTile\SellBriteStore;
use RankFoundry\SellBriteTile\Services\SellBrite;

class FetchSellBriteFulfillmentsCommand extends Command
{
    protected $signature = 'dashboard:fetch-sellbrite-fulfillments';

    protected $description = 'Fetches items needing to be shipped from SellBrite';

    public function handle()
    {
        $this->info('Fetching SellBrite Fulfillment Data...');

        SellBriteStore::make()->setFulfillments(
                SellBrite::getFulfillments(
                config('dashboard.tiles.sellbrite.api_token'),
                config('dashboard.tiles.sellbrite.api_key'),
                config('dashboard.tiles.sellbrite.warehouse_uuid'),
            ));

        $this->info('All done!');
    }
}