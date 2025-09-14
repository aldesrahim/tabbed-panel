<?php

use Aldesrahim\TabbedPanel\Stores\DatabaseStore;
use Aldesrahim\TabbedPanel\Stores\SessionStore;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default storage mechanism used by the TabbedPanel.
    | Supported: "session", "database"
    |
    */
    'default' => env('TABBED_PANEL_STORE', 'session'),

    /*
    |--------------------------------------------------------------------------
    | Stores
    |--------------------------------------------------------------------------
    |
    | Here you may configure the store implementations for TabbedPanel.
    | You can define multiple stores, each with its own configuration.
    |
    */
    'stores' => [

        'session' => [
            'driver' => SessionStore::class,
        ],

        'database' => [
            'driver' => DatabaseStore::class,
        ],
    ],
];
