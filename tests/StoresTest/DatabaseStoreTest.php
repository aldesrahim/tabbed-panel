<?php

use Aldesrahim\TabbedPanel\Facades\TabbedPanel;
use Aldesrahim\TabbedPanel\Stores\DatabaseStore;
use Illuminate\Support\Str;

it('can open tabs', function () {
    TabbedPanel::setContext(1);
    TabbedPanel::setStore(new DatabaseStore);

    expect(TabbedPanel::getTabs())->toBe([]);

    TabbedPanel::addTab(['key' => $firstTab = Str::random()]);
    TabbedPanel::addTab(['key' => $secondTab = Str::random()]);

    expect(TabbedPanel::hasTab($firstTab))->toBeTrue();
    expect(TabbedPanel::hasTab($secondTab))->toBeTrue();
    expect(TabbedPanel::getActiveTab())->toBe($secondTab);
    expect(TabbedPanel::getTabsOrder())->toBe([$firstTab, $secondTab]);
});

it('can close tabs', function () {
    TabbedPanel::setContext(1);
    TabbedPanel::setStore(new DatabaseStore);

    TabbedPanel::addTab(['key' => $firstTab = Str::random()]);
    TabbedPanel::addTab(['key' => $secondTab = Str::random()]);
    TabbedPanel::addTab(['key' => $thirdTab = Str::random()]);

    TabbedPanel::removeTab($secondTab);

    expect(TabbedPanel::hasTab($secondTab))->toBeFalse();
    expect(TabbedPanel::getActiveTab())->toBe($thirdTab);
    expect(TabbedPanel::getTabsOrder())->toBe([$firstTab, $thirdTab]);

    TabbedPanel::removeTab($thirdTab);

    expect(TabbedPanel::hasTab($thirdTab))->toBeFalse();
    expect(TabbedPanel::getActiveTab())->toBe($firstTab);
    expect(TabbedPanel::getTabsOrder())->toBe([$firstTab]);
});

it('can active other tab', function () {
    TabbedPanel::setContext(1);
    TabbedPanel::setStore(new DatabaseStore);

    TabbedPanel::addTab(['key' => $firstTab = Str::random()]);
    TabbedPanel::addTab(['key' => $secondTab = Str::random()]);
    TabbedPanel::addTab(['key' => $thirdTab = Str::random()]);

    expect(TabbedPanel::getActiveTab())->toBe($thirdTab);

    TabbedPanel::setActiveTab($firstTab);
    expect(TabbedPanel::getActiveTab())->toBe($firstTab);

    TabbedPanel::setActiveTab($secondTab);
    expect(TabbedPanel::getActiveTab())->toBe($secondTab);
});

it('can get tabs', function () {
    TabbedPanel::setContext(1);
    TabbedPanel::setStore(new DatabaseStore);

    $tabs = [];

    expect(TabbedPanel::getTabs())->toBeEmpty();

    TabbedPanel::addTab(['key' => $firstTab = Str::random()]);
    $tabs[] = $firstTab;
    expect(array_keys(TabbedPanel::getTabs()))->toBe($tabs);

    TabbedPanel::addTab(['key' => $secondTab = Str::random()]);
    $tabs[] = $secondTab;
    expect(array_keys(TabbedPanel::getTabs()))->toBe($tabs);

    TabbedPanel::addTab(['key' => $thirdTab = Str::random()]);
    $tabs[] = $thirdTab;
    expect(array_keys(TabbedPanel::getTabs()))->toBe($tabs);
});

it('ignore remove not existing tab', function () {
    TabbedPanel::setContext(1);
    TabbedPanel::setStore(new DatabaseStore);

    expect(TabbedPanel::removeTab('not_exists'))->toBeNull();
});
