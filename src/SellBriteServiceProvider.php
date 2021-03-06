<?php

namespace RankFoundry\SellBriteTile;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use RankFoundry\SellBriteTile\Commands\FetchSellBriteFulfillmentsCommand;
use RankFoundry\SellBriteTile\Commands\FetchSellBriteSalesCommand;
use RankFoundry\SellBriteTile\Commands\FetchSellBriteErrorsCommand;
use RankFoundry\SellBriteTile\Components\SellBriteFulfillmentsComponent;
use RankFoundry\SellBriteTile\Components\SellBriteSalesComponent;
use RankFoundry\SellBriteTile\Components\SellBriteErrorsComponent;

class SellBriteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchSellBriteFulfillmentsCommand::class,
                FetchSellBriteSalesCommand::class,
                FetchSellBriteErrorsCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dashboard-sellbrite-tiles'),
        ], 'dashboard-sellbrite-tiles');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-sellbrite-tiles');

        Livewire::component('sellbrite-fulfillments-tile', SellBriteFulfillmentsComponent::class);
        Livewire::component('sellbrite-sales-tile', SellBriteSalesComponent::class);
        Livewire::component('sellbrite-errors-tile', SellBriteErrorsComponent::class);
    }
}