<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    | The location of your settings file. This should be the absolute path,
    | including the filename and extension (.json).
    |
    */

    'path'           => storage_path('settings.json'),
    'namespace_path' => storage_path('settings_json'),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Your settings will be cached indefinitely until a value changes. You can
    | use these configuration options to change the cache key and the tag that
    | gets used.
    |
    */

    'cache' => [
        'key'                  => 'json-settings',
        'key_prefix_namespace' => 'json-settings__',
        'tag'                  => 'json-settings',
    ],

];
