<?php

use Aldesrahim\TabbedPanel\Facades\TabbedPanel;
use Aldesrahim\TabbedPanel\TabbedPanelManager;

it('fail to resolves non existing store name', function () {
    app(TabbedPanelManager::class)->setDefaultDriver('not_exists');

    TabbedPanel::setContext(1);
})->throws(\InvalidArgumentException::class, 'TabbedPanel store [not_exists] is not defined.');

it('fail to resolves non existing store driver class', function () {
    config(['tabbed-panel.stores.session.driver' => 'NotExistDriverClass']);

    TabbedPanel::setContext(1);
})->throws(\InvalidArgumentException::class, 'TabbedPanel store driver [NotExistDriverClass] does not exist.');
