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

// Pertahankan URI asli dari Vercel.
$originalRequestUri = $_SERVER['REQUEST_URI'] ?? '/';
$_SERVER['REQUEST_URI'] = $originalRequestUri;

// Hilangkan identitas router PHP dari base URL Laravel.
$_SERVER['SCRIPT_NAME'] = '/__vercel_front_controller.php';
$_SERVER['PHP_SELF'] = '/__vercel_front_controller.php';

// Hapus PATH_INFO agar Laravel membaca URI asli.
unset(
    $_SERVER['PATH_INFO'],
    $_SERVER['ORIG_PATH_INFO'],
    $_SERVER['ORIG_SCRIPT_NAME']
);

// Diagnostik sementara.
error_log('RUANGBACA_FIXED_URI: ' . $_SERVER['REQUEST_URI']);
error_log('RUANGBACA_FIXED_SCRIPT: ' . $_SERVER['SCRIPT_NAME']);
error_log(
    'RUANGBACA_FIXED_PATH_INFO: ' .
    ($_SERVER['PATH_INFO'] ?? '[UNSET]')
);

// Jalankan Laravel.
require __DIR__ . '/../public/index.php';