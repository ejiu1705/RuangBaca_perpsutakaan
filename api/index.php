<?php

// Direktori sementara untuk compiled Blade Views.
$compiledViews = getenv('VIEW_COMPILED_PATH') ?: '/tmp/ruangbaca/views';

// Pastikan direktori tersedia.
if (
    !is_dir($compiledViews) &&
    !@mkdir($compiledViews, 0755, true) &&
    !is_dir($compiledViews)
) {
    error_log('RuangBaca: folder VIEW_COMPILED_PATH tidak dapat dibuat.');
    http_response_code(500);
    exit('Layanan sementara tidak tersedia.');
}

// Teruskan konfigurasi ke environment Laravel.
putenv('VIEW_COMPILED_PATH=' . $compiledViews);
$_ENV['VIEW_COMPILED_PATH'] = $compiledViews;
$_SERVER['VIEW_COMPILED_PATH'] = $compiledViews;
// Diagnostik URI request dari Vercel.
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

error_log(
    'RUANGBACA_DIAG_URI_PATH: ' .
    (parse_url($requestUri, PHP_URL_PATH) ?: '[EMPTY]')
);

foreach ([
    'SCRIPT_NAME',
    'PHP_SELF',
    'PATH_INFO',
    'REDIRECT_URL',
    'HTTP_X_NOW_ROUTE_MATCHES',
] as $key) {
    error_log(
        'RUANGBACA_DIAG_' . $key . ': ' .
        ($_SERVER[$key] ?? '[NOT SET]')
    );
}

// Diagnostik sementara untuk Runtime Logs Vercel.
// Pertahankan URL asli yang dikirim oleh Vercel.
$originalRequestUri = $_SERVER['REQUEST_URI'] ?? '/';
$_SERVER['REQUEST_URI'] = $originalRequestUri;

// Hindari PHP menganggap /api/index.php sebagai base URL Laravel.
$_SERVER['SCRIPT_NAME'] = '/__vercel_front_controller.php';
$_SERVER['PHP_SELF'] = '/__vercel_front_controller.php';

// Hapus informasi path milik router PHP Vercel.
unset(
    $_SERVER['PATH_INFO'],
    $_SERVER['ORIG_PATH_INFO'],
    $_SERVER['ORIG_SCRIPT_NAME']
);

// Diagnostik sementara.
error_log('RUANGBACA_NORMALIZED_URI: ' . $_SERVER['REQUEST_URI']);
error_log('RUANGBACA_NORMALIZED_SCRIPT: ' . $_SERVER['SCRIPT_NAME']);

// Jalankan Laravel.
require __DIR__ . '/../public/index.php';
