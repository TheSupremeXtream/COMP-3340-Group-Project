<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'store_db');

define('SITE_NAME', 'The Computer Store');
define('BASE_URL',  'http://localhost/COMP-3340-Group-Project/');
define('IMG_PATH',  BASE_URL . 'images/');

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

function get_allowed_themes(): array {
    return ['light', 'dark', 'holiday'];
}

function normalize_theme(string $theme): string {
    return in_array($theme, get_allowed_themes(), true) ? $theme : 'light';
}

function get_active_theme(): string {
    try {
        $pdo  = get_db();
        $stmt = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'active_theme' LIMIT 1");
        $row  = $stmt->fetch();
        return normalize_theme($row ? (string) $row['setting_value'] : 'light');
    } catch (PDOException $e) {
        return 'light';
    }
}

function set_active_theme(string $theme): bool {
    $theme = normalize_theme($theme);
    try {
        $pdo = get_db();
        $check = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = :setting_key");
        $check->execute([':setting_key' => 'active_theme']);
        $exists = (int) $check->fetchColumn() > 0;
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :theme WHERE setting_key = :setting_key");
            return $stmt->execute([':theme' => $theme, ':setting_key' => 'active_theme']);
        }
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:setting_key, :theme)");
        return $stmt->execute([':setting_key' => 'active_theme', ':theme' => $theme]);
    } catch (PDOException $e) {
        return false;
    }
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}