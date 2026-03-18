<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'store_db');

define('SITE_NAME', 'comp3340');
define('BASE_URL',  'http://localhost/comp3340/');
define('IMG_PATH',  BASE_URL . 'assets/images/');

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}

function get_active_theme(): string {
    try {
        $pdo  = get_db();
        $stmt = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'active_theme'");
        $row  = $stmt->fetch();
        return $row ? $row['setting_value'] : 'default';
    } catch (PDOException $e) {
        return 'default';
    }
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
