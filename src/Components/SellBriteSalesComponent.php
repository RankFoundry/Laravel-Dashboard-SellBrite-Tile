<?php

namespace RankFoundry\SellBriteTile\Components;

use Livewire\Component;
use RankFoundry\SellBriteTile\SellBriteStore;
use RankFoundry\SellBriteTile\Services\SellBrite;

class SellBriteSalesComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('dashboard-sellbrite-tiles::sales.tile', [
          'refreshIntervalInSeconds' => config('dashboard.tiles.sellbrite.refresh_interval_in_seconds') ?? 60,
          'sales' => SellBriteStore::make()->sales()
        ]);
    }
}