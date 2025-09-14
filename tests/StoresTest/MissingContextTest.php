<?php

use Aldesrahim\TabbedPanel\Exceptions\MissingStoreContextException;
use Aldesrahim\TabbedPanel\Facades\TabbedPanel;

it('throws missing store context', function () {
    TabbedPanel::getTabs();
})->throws(MissingStoreContextException::class);
