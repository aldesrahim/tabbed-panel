<?php

namespace Aldesrahim\TabbedPanel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Aldesrahim\TabbedPanel\TabbedPanel
 */
class TabbedPanel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Aldesrahim\TabbedPanel\TabbedPanel::class;
    }
}
