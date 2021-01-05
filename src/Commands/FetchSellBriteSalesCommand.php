<?php

namespace RankFoundry\SellBriteTile\Commands;

use Illuminate\Console\Command;
use RankFoundry\SellBriteTile\SellBriteStore;
use RankFoundry\SellBriteTile\Services\SellBrite;

class FetchSellBriteSalesCommand extends Command
{
    protected $signature = 'dashboard:fetch-sellbrite-sales';

    protected $description = 'Fetches sales data from SellBrite';

    public function handle()
    {
        $this->info('Fetching SellBrite Sales Data...');

        SellBriteStore::make()->setSales(
                SellBrite::getSales(
                config('dashboard.tiles.sellbrite.api_token'),
                config('dashboard.tiles.sellbrite.api_key')
            ));

        $this->info('All done!');
    }
}