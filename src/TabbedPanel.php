<?php

namespace Aldesrahim\TabbedPanel;

use Aldesrahim\TabbedPanel\Exceptions\MissingCurrentUserException;
use Aldesrahim\TabbedPanel\Stores\Contracts\StoreContract;
use Aldesrahim\TabbedPanel\Stores\SessionStore;

class TabbedPanel
{
    private ?StoreContract $store = null;

    private ?Context $context = null;

    public function __construct(?StoreContract $store = null)
    {
        $this->store = $store ?? new SessionStore;
    }

    public function setStore(StoreContract $store): void
    {
        $this->store = $store;

        if ($this->context) {
            $this->store->setContext($this->context);
        }
    }

    public function setContext(int | string $userId, int | string | null $tenantId = null): void
    {
        if (empty($userId)) {
            throw new MissingCurrentUserException('The current user must be set and cannot be empty');
        }

        $this->context = new Context($userId, $tenantId);
        $this->store?->setContext($this->context);
    }

    private function store(): StoreContract
    {
        if ($this->context) {
            $this->store->setContext($this->context);
        }

        return $this->store;
    }

    public function getTabs(): array
    {
        return $this->store()->getTabs();
    }

    public function addTab(array $tab, bool $active = true): void
    {
        $this->store()->addTab($tab, $active);
    }

    public function hasTab(string $tabKey): bool
    {
        return $this->store()->hasTab($tabKey);
    }

    public function removeTab(string $tabKey): void
    {
        $this->store()->removeTab($tabKey);
    }

    public function getActiveTab(): ?string
    {
        return $this->store()->getActiveTab();
    }

    public function setActiveTab(?string $tabKey): void
    {
        $this->store()->setActiveTab($tabKey);
    }

    public function getTabsOrder(): array
    {
        return $this->store()->getTabsOrder();
    }
}
