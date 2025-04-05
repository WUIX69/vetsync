<?php

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', dirname(dirname(dirname(__DIR__))) . '/logs/error.log');

function tryCatch($callback, $errorMessage = "Error: ")
{
    try {
        return $callback();
    } catch (Throwable $t) {
        error_log($errorMessage . $t->getMessage());
        // echo $errorMessage . $t->getMessage();
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

function sysfHelper($dir, $file)
{
    // Define the directory where your files are located
    $dir_path = dirname(dirname(__DIR__)) . '/' . $dir . '/';
    // Construct the full path to the file
    $file_path = $dir_path . $file . '.php';

    tryCatch(function () use ($file_path) {
        if (!file_exists($file_path)) {
            throw new Exception("File not found: $file_path");
        }
        include $file_path; // Include the file
    }, "$dir file Error, ");
}

function srcfHelper($dir, $file)
{
    // Define the directory where your source files are located
    $web_path = $dir . '/' . ltrim($file, '/');
    // Return the URL of the source file
    return baseURL($web_path);
}

function statf($file)
{
    return srcfHelper('public', $file);
}

function app($link = '')
{
    $url = $link . (strpos($link, '/') === false ? '/' : '' . '.php');
    return srcfHelper('src/app', $url);
}

function shared($file, $is_src = false)
{
    // Early return if src base url
    if ($is_src) {
        return srcfHelper('src/shared', $file);
    }
    // system file base url
    sysfHelper('shared', $file);
}

function feature($path, $is_src = false)
{
    if ($is_src) {
        return srcfHelper('src/features', $path);
    }

    sysfHelper('features', $path);
}
