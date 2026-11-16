<?php

$compiledViewsPath = env('VIEW_COMPILED_PATH');

if (! is_string($compiledViewsPath) || trim($compiledViewsPath) === '') {
    $compiledViewsPath = env('APP_ENV') === 'production'
        ? sys_get_temp_dir() . '/ruangbaca/views'
        : (realpath(storage_path('framework/views')) ?: storage_path('framework/views'));
}

return [
    'paths' => [
        resource_path('views'),
    ],

    'compiled' => $compiledViewsPath,
];
