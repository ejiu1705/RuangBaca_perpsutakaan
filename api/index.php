<?php

// Vercel's deployment filesystem is read-only; compiled Blade views must live in /tmp.
$compiledViews = getenv('VIEW_COMPILED_PATH') ?: '/tmp/ruangbaca/views';

if (! is_dir($compiledViews) && ! @mkdir($compiledViews, 0755, true) && ! is_dir($compiledViews)) {
    error_log('RuangBaca: folder VIEW_COMPILED_PATH tidak dapat dibuat.');
    http_response_code(500);
    exit('Layanan sementara tidak tersedia.');
}

require __DIR__ . '/../public/index.php';

