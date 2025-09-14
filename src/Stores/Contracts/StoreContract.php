<?php

namespace Aldesrahim\TabbedPanel\Stores\Contracts;

use Aldesrahim\TabbedPanel\Context;

interface StoreContract
{
    public function setContext(Context $context): void;

    public function getTabs(): array;

    public function addTab(array $tab, bool $active = true): void;

    public function hasTab(string $tabKey): bool;

    public function removeTab(string $tabKey): void;

    public function getActiveTab(): ?string;

    public function setActiveTab(?string $tabKey): void;

    public function getTabsOrder(): array;
}
