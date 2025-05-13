<?php
class Config {
    private static $config = [];

    public static function load() {
        // Read .env file
        $envFile = __DIR__ . '/.env';
        if (!file_exists($envFile)) {
            die('.env file not found');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse env variables
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes if present
                $value = trim($value, '"\'');

                // Replace variables in values (e.g., ${APP_NAME})
                $value = preg_replace_callback('/\${([A-Za-z0-9_]+)}/', function($matches) {
                    return self::$config[$matches[1]] ?? '';
                }, $value);

                self::$config[$key] = $value;
            }
        }

        // Apply PHP settings
        ini_set('memory_limit', self::get('PHP_MEMORY_LIMIT'));
        ini_set('max_execution_time', self::get('PHP_MAX_EXECUTION_TIME'));
        ini_set('upload_max_filesize', self::get('PHP_UPLOAD_MAX_FILESIZE'));
        ini_set('post_max_size', self::get('PHP_POST_MAX_SIZE'));
        ini_set('error_reporting', self::get('PHP_ERROR_REPORTING'));
        ini_set('display_errors', self::get('PHP_DISPLAY_ERRORS'));
        ini_set('log_errors', self::get('PHP_LOG_ERRORS'));
        ini_set('error_log', self::get('PHP_ERROR_LOG'));

        // Set timezone
        date_default_timezone_set(self::get('TIMEZONE'));

        // Initialize session
        session_name(self::get('SESSION_NAME'));
        ini_set('session.gc_maxlifetime', self::get('SESSION_LIFETIME') * 60);
        session_start();
    }

    public static function get($key, $default = null) {
        return self::$config[$key] ?? $default;
    }

    // Database connection helper
    public static function getDB() {
        try {
            $conn = new PDO(
                "mysql:host=" . self::get('DB_HOST') .
                ";dbname=" . self::get('DB_DATABASE') .
                ";port=" . self::get('DB_PORT'),
                self::get('DB_USERNAME'),
                self::get('DB_PASSWORD')
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
