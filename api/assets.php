<?php

/**
 * Menyajikan aset hasil Vite build melalui runtime PHP.
 * Runtime vercel-php menjalankan Composer script `vercel` pada fase build;
 * aset yang dihasilkan berada di dalam paket fungsi PHP dan dilayani lewat endpoint ini.
 */

$relativePath = (string) ($_GET['path'] ?? '');
$relativePath = ltrim($relativePath, '/');

$invalidPath = $relativePath === ''
    || str_contains($relativePath, "\0")
    || str_contains($relativePath, '\\')
    || preg_match('~(^|/)\.{1,2}(/|$)~', $relativePath) === 1
    || preg_match('~[^A-Za-z0-9._/-]~', $relativePath) === 1;

if ($invalidPath) {
    http_response_code(404);
    exit;
}

$mimeTypes = [
    'css' => 'text/css; charset=utf-8',
    'js' => 'application/javascript; charset=utf-8',
    'svg' => 'image/svg+xml',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
    'avif' => 'image/avif',
    'gif' => 'image/gif',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'otf' => 'font/otf',
];

$extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
if (! isset($mimeTypes[$extension])) {
    http_response_code(404);
    exit;
}

$buildDirectory = realpath(__DIR__ . '/../public/build');
if ($buildDirectory === false) {
    http_response_code(404);
    exit;
}

$requestedFile = realpath($buildDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
if (
    $requestedFile === false
    || ! str_starts_with($requestedFile, $buildDirectory . DIRECTORY_SEPARATOR)
    || ! is_file($requestedFile)
) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $mimeTypes[$extension]);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=31536000, immutable');
header('Content-Length: ' . (string) filesize($requestedFile));
readfile($requestedFile);
