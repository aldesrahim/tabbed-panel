<?php

namespace Aldesrahim\TabbedPanel\Stores;

use Aldesrahim\TabbedPanel\Context;
use Aldesrahim\TabbedPanel\Exceptions\MissingStoreContextException;
use Illuminate\Support\Facades\Session;

class SessionStore implements Contracts\StoreContract
{
    public const SESSION_KEY = 'tabbed_panel';

    public const TABS_KEY = 'tabs';

    public const TABS_ORDER_KEY = 'tabs_order';

    public const ACTIVE_TAB_KEY = 'active_tab';

    private Context $context;

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    private function context(): Context
    {
        if (! isset($this->context)) {
            throw new MissingStoreContextException('Context must be set before using the store.');
        }

        return $this->context;
    }

    public function getTabs(): array
    {
        return Session::get($this->sessionKey(self::TABS_KEY), []);
    }

    public function addTab(array $tab, bool $active = true): void
    {
        $tabKey = $tab['key'];

        // Update tab data
        $tabs = $this->getTabs();
        $tabs[$tabKey] = $tab;
        $this->putSession(self::TABS_KEY, $tabs);

        // Update tab order (ensure unique)
        $order = $this->getTabsOrder();

        if (! in_array($tabKey, $order, true)) {
            $order[] = $tabKey;
            $this->putSession(self::TABS_ORDER_KEY, $order);
        }

        if ($active) {
            $this->setActiveTab($tabKey);
        }
    }

    public function hasTab(string $tabKey): bool
    {
        return in_array($tabKey, $this->getTabsOrder(), true);
    }

    public function removeTab(string $tabKey): void
    {
        $tabs = $this->getTabs();
        $tabsOrder = $this->getTabsOrder();

        // Handle active tab reassignment
        if ($tabKey === $this->getActiveTab()) {
            $nextTab = $this->determineNextActiveTab($tabKey, $tabsOrder);
            $this->setActiveTab($nextTab);
        }

        // Remove tab data
        unset($tabs[$tabKey]);
        $this->putSession(self::TABS_KEY, $tabs);

        // Remove from order
        $tabsOrder = array_values(array_filter($tabsOrder, fn ($id) => $id !== $tabKey));
        $this->putSession(self::TABS_ORDER_KEY, $tabsOrder);

    }

    public function getActiveTab(): ?string
    {
        return $this->getSession(self::ACTIVE_TAB_KEY);
    }

    public function setActiveTab(?string $tabKey): void
    {
        $this->putSession(self::ACTIVE_TAB_KEY, $tabKey);
    }

    public function getTabsOrder(): array
    {
        return $this->getSession(self::TABS_ORDER_KEY, []);
    }

    private function sessionKey(string $suffix): string
    {
        $userId = $this->context()->userId;
        $tenantId = $this->context()->tenantId ?? 'no_tenant';
        $baseKey = sprintf('%s:%s', self::SESSION_KEY, hash('xxh128', $userId . $tenantId));

        return "$baseKey:$suffix";
    }

    private function getSession(string $suffix, mixed $default = null): mixed
    {
        return Session::get($this->sessionKey($suffix), $default);
    }

    private function putSession(string $suffix, mixed $value): void
    {
        Session::put($this->sessionKey($suffix), $value);
    }

    private function determineNextActiveTab(string $closingTab, array $tabsOrder): ?string
    {
        $index = array_search($closingTab, $tabsOrder, true);

        if ($index === false || count($tabsOrder) <= 1) {
            return null;
        }

        $newIndex = ($index === count($tabsOrder) - 1) ? $index - 1 : $index + 1;

        return $tabsOrder[$newIndex] ?? null;
    }
}
