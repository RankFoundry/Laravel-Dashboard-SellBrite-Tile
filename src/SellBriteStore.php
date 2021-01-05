<?php

namespace RankFoundry\SellBriteTile;

use Spatie\Dashboard\Models\Tile;

class SellBriteStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName("sellbrite");
    }

    public function setFulfillments(array $fulfillments) : self
    {
        $this->tile->putData('sellbrite:fulfillments', $fulfillments);

        return $this;
    }

    public function fulfillments()
    {
        return $this->tile->getData('sellbrite:fulfillments') ?? [];
    }

    public function setSales(array $sales): self
    {
        $this->tile->putData('sellbrite:sales', $sales);

        return $this;
    }

    public function sales()
    {
        return $this->tile->getData('sellbrite:sales') ?? [];
    }

    public function setErrors(array $errors): self
    {
        $this->tile->putData('sellbrite:errors', $errors);

        return $this;
    }

    public function errors()
    {
        return $this->tile->getData('sellbrite:errors') ?? [];
    }

    public function setData(array $data): self
    {
        $this->tile->putData('key', $data);

        return $this;
    }

    public function getData(): array
    {
        return$this->tile->getData('key') ?? [];
    }
}