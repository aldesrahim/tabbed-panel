<?php

use Aldesrahim\TabbedPanel\Exceptions\MissingCurrentUserException;
use Aldesrahim\TabbedPanel\Facades\TabbedPanel;

it('throws missing current user', function () {
    TabbedPanel::setContext('');
})->throws(MissingCurrentUserException::class);
