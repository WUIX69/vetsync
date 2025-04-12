<?php

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/error.log');

function tryCatch($callback, $errorMessage = "Error: ")
{
    try {
        return $callback();
    } catch (Throwable $t) {
        error_log($errorMessage . $t->getMessage());
        return false;
    }
}

function baseURL($path = '')
{
    // Get the protocol and host only once per page load
    static $baseUrl = null;

    // Get the base URL if it's not already set
    if ($baseUrl === null) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . '://' . $host . '/';
    }

    // Return base URL if no path provided
    if (empty($path)) {
        return $baseUrl;
    }

    // Append the path
    return $baseUrl . ltrim($path, '/');
}

function includeFileHelper($dir, $file)
{
    // Define the directory where your files are located
    $dir_path = dirname(dirname(__DIR__)) . '/' . $dir . '/';

    // error_log('dirname: ' . dirname(dirname(__DIR__)));
    // error_log('dir_path: ' . $_SERVER['DOCUMENT_ROOT'] . '/src/');

    // Construct the full path to the file
    $file_path = $dir_path . $file . '.php';

    tryCatch(function () use ($file_path) {
        if (!file_exists($file_path)) {
            throw new Exception("File not found: $file_path");
        }
        include $file_path; // Include the file
    }, "$dir file Error, ");
}

function urlFileHelper($dir, $file, $is_public = false)
{
    // Define the directory where your source files are located
    $dir_path = $is_public ? 'public' : "src/$dir";
    $url = $dir_path . '/' . ltrim($file, '/');
    // Return the URL of the source file
    return baseURL($url);
}

function statf($file)
{
    return urlFileHelper('public', $file, true);
}

function shared($file, $is_url = false)
{
    if ($is_url)
        return urlFileHelper('shared', $file);
    includeFileHelper('shared', $file);
}

function featured($path, $is_url = false)
{
    if ($is_url)
        return urlFileHelper('features', $path);
    includeFileHelper('features', $path);
}

function utils($file, $is_url = false)
{
    if ($is_url)
        return urlFileHelper('utils', $file);
    includeFileHelper('utils', $file);
}

function app($link = '')
{
    // Handle empty link case
    if (empty($link)) {
        return urlFileHelper('app', 'landing');
    }

    // Check if the link ends with a trailing slash (indicating a directory)
    if (substr($link, -1) === '/') {
        $url = $link . 'index.php';
    }
    // Check if the link has no slashes (just a directory name)
    elseif (strpos($link, '/') === false) {
        $url = $link . '/index.php';
    }
    // Handle paths with subdirectories
    else {
        // Check if the path contains a file extension
        if (preg_match('/\.[a-zA-Z0-9]+$/', $link)) {
            // If it already has an extension, use it as is
            $url = $link;
        } else {
            // No extension, so add .php
            $url = $link . '.php';
        }
    }

    // Ensure we have the .php extension
    if (substr($url, -4) !== '.php') {
        $url .= '.php';
    }

    return urlFileHelper('app', $url);
}
