<?php

/**
 * CampusAround — Vercel Serverless Function Bridge
 *
 * Forwards serverless requests on Vercel to Laravel's public entrypoint.
 * Automatically prepares the writable /tmp directory for Blade view compilation.
 */

// Ensure writable directories in /tmp for Vercel's read-only serverless filesystem
$tmpDirs = [
    '/tmp/views',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward to Laravel's public entry point
require __DIR__ . '/../public/index.php';
