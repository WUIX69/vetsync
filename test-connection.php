<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/core/config.php';
require_once __DIR__ . '/src/core/conn.php';

echo "<h1>Database Connection Test</h1>";

try {
    // Test query
    $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();

    echo "<p style='color: green;'>✅ Connection successful!</p>";
    echo "<p>Database: " . ($config['db']['connection']) . "</p>";
    echo "<p>Host: " . ($config['db']['host']) . "</p>";
    echo "<p>Total users: " . $result['total'] . "</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
}