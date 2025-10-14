<?php

$conn = null;
try {
    // Determine DSN based on DB_CONNECTION type
    $dbConnection = $config['db']['connection'] ?? 'mysql';

    if ($dbConnection === 'pgsql') {
        // PostgreSQL connection for Supabase
        $dsn = "pgsql:host=" . $config['db']['host'] .
            ";port=" . $config['db']['port'] .
            ";dbname=" . $config['db']['name'];
    } else {
        // MySQL connection for local development
        $dsn = "mysql:host=" . $config['db']['host'] .
            ";dbname=" . $config['db']['name'] .
            ";port=" . $config['db']['port'];
    }

    $conn = new PDO(
        $dsn,
        $config['db']['username'],
        $config['db']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

/* 
 * Now you can use this connection by accessing 
 * the global $conn variable
 * 
 * Example usage:
 * --------------
 * global $conn;
 * $stmt = $conn->query("SELECT * FROM users");
 * 
 */