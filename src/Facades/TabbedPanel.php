<?php

namespace Aldesrahim\TabbedPanel\Facades;

use Aldesrahim\TabbedPanel\Stores\Contracts\StoreContract;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Aldesrahim\TabbedPanel\TabbedPanel
 *
 * @method static void setStore(StoreContract $store)
 * @method static void setContext(int | string $userId, int | string | null $tenantId = null)
 * @method static array getTabs()
 * @method static void addTab(array $tab, bool $active = true)
 * @method static bool hasTab(string $tabKey)
 * @method static void removeTab(string $tabKey)
 * @method static ?string getActiveTab()
 * @method static void setActiveTab(?string $tabKey)
 * @method static array getTabsOrder()
 */
class TabbedPanel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Aldesrahim\TabbedPanel\TabbedPanel::class;
    }
}
