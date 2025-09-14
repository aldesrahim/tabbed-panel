<?php

use Aldesrahim\TabbedPanel\Facades\TabbedPanel;
use Illuminate\Support\Str;

it('can open tabs', function () {
    TabbedPanel::setContext(1);

    TabbedPanel::addTab(['key' => $firstTab = Str::random()]);
    TabbedPanel::addTab(['key' => $secondTab = Str::random()]);

    expect(TabbedPanel::hasTab($firstTab))->toBeTrue();
    expect(TabbedPanel::hasTab($secondTab))->toBeTrue();
    expect(TabbedPanel::getActiveTab())->toBe($secondTab);
    expect(TabbedPanel::getTabsOrder())->toBe([$firstTab, $secondTab]);
});

it('can close tabs', function () {
    TabbedPanel::setContext(1);

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

    TabbedPanel::removeTab($firstTab);
    expect(TabbedPanel::getActiveTab())->toBeNull();
});

it('can active other tab', function () {
    TabbedPanel::setContext(1);

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

    $tabs = [];

    expect(TabbedPanel::getTabs())->toBeEmpty();

    TabbedPanel::addTab($firstTab = ['key' => Str::random()]);
    $tabs[$firstTab['key']] = $firstTab;
    expect(TabbedPanel::getTabs())->toBe($tabs);

    TabbedPanel::addTab($secondTab = ['key' => Str::random()]);
    $tabs[$secondTab['key']] = $secondTab;
    expect(TabbedPanel::getTabs())->toBe($tabs);

    TabbedPanel::addTab($thirdTab = ['key' => Str::random()]);
    $tabs[$thirdTab['key']] = $thirdTab;
    expect(TabbedPanel::getTabs())->toBe($tabs);
});
