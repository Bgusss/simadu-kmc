<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is used as a router script for PHP's built-in server.
 * It allows Laravel to handle all requests just like artisan serve does.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If the request is for a real file, serve it directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
