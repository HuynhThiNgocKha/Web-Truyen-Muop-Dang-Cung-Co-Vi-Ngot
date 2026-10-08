<?php
/**
 * Router script for PHP built-in web server
 */
$root = __DIR__;
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 1. Mobile connect helper
if ($uri === '/mobile.html' || $uri === '/mobile') {
    require $root . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'mobile.html';
    return true;
}

// 2. Direct static files
$filePath = realpath($root . $uri);
if ($filePath && is_file($filePath)) {
    return false;
}

// 3. Core subdirectory transparent fallback (wp-admin, wp-login, wp-includes, etc.)
if (preg_match('#^/(wp-admin|wp-includes|wp-login\.php|wp-cron\.php|wp-comments-post\.php)(/.*)?$#', $uri)) {
    $coreTarget = $root . DIRECTORY_SEPARATOR . 'core' . str_replace('/', DIRECTORY_SEPARATOR, $uri);
    if (is_file($coreTarget)) {
        $ext = strtolower(pathinfo($coreTarget, PATHINFO_EXTENSION));
        if ($ext === 'php') {
            $_SERVER['SCRIPT_NAME'] = '/core' . $uri;
            $_SERVER['SCRIPT_FILENAME'] = $coreTarget;
            require $coreTarget;
            return true;
        } else {
            $mimeTypes = [
                'css'   => 'text/css',
                'js'    => 'application/javascript',
                'png'   => 'image/png',
                'jpg'   => 'image/jpeg',
                'jpeg'  => 'image/jpeg',
                'gif'   => 'image/gif',
                'svg'   => 'image/svg+xml',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf'   => 'font/ttf',
                'ico'   => 'image/x-icon',
            ];
            if (isset($mimeTypes[$ext])) {
                header('Content-Type: ' . $mimeTypes[$ext]);
            }
            readfile($coreTarget);
            return true;
        }
    } elseif (is_dir($coreTarget)) {
        $indexFile = rtrim($coreTarget, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'index.php';
        if (is_file($indexFile)) {
            $_SERVER['SCRIPT_NAME'] = '/core' . rtrim($uri, '/') . '/index.php';
            $_SERVER['SCRIPT_FILENAME'] = $indexFile;
            require $indexFile;
            return true;
        }
    }
}

// 4. Default: WordPress Front Controller
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root . DIRECTORY_SEPARATOR . 'index.php';
require $root . DIRECTORY_SEPARATOR . 'index.php';
