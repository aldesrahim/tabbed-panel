<?php

namespace Aldesrahim\TabbedPanel;

use Aldesrahim\TabbedPanel\Stores\Contracts\StoreContract;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class TabbedPanelManager
{
    protected array $stores = [];

    protected ?string $defaultDriver = null;

    public function store(?string $name = null): StoreContract
    {
        $name = $name ?? $this->getDefaultDriver();

        if (! isset($this->stores[$name])) {
            $this->stores[$name] = $this->resolve($name);
        }

        return $this->stores[$name];
    }

    protected function resolve(string $name): StoreContract
    {
        $config = config("tabbed-panel.stores.$name");

        if (is_null($config)) {
            throw new InvalidArgumentException("TabbedPanel store [{$name}] is not defined.");
        }

        $driver = Arr::get($config, 'driver');

        if (! class_exists($driver)) {
            throw new InvalidArgumentException("TabbedPanel store driver [{$driver}] does not exist.");
        }

        return app($driver);
    }

    public function getDefaultDriver(): string
    {
        return $this->defaultDriver ?? config('tabbed-panel.default');
    }

    public function setDefaultDriver(string $name): void
    {
        $this->defaultDriver = $name;
    }
}
