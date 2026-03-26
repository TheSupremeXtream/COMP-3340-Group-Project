<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'store_db');

define('SITE_NAME', 'The Computer Store');

function get_base_url(): string
{
    if (PHP_SAPI === 'cli') {
        return 'http://localhost/';
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 80) === 443);

    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $project_root_fs = str_replace('\\', '/', realpath(dirname(__DIR__)) ?: dirname(__DIR__));
    $document_root_fs = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: ($_SERVER['DOCUMENT_ROOT'] ?? ''));

    $project_path = '';

    if ($document_root_fs !== '' && str_starts_with($project_root_fs, $document_root_fs)) {
        $project_path = substr($project_root_fs, strlen($document_root_fs));
    }

    if ($project_path === false) {
        $project_path = '';
    }

    $project_path = '/' . trim(str_replace('\\', '/', $project_path), '/');
    $base = $scheme . '://' . $host;

    if ($project_path === '/') {
        return $base . '/';
    }

    return $base . $project_path . '/';
}

define('BASE_URL', get_base_url());
define('IMG_PATH', BASE_URL . 'images/');

function get_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}

function get_allowed_themes(): array
{
    return ['light', 'dark', 'holiday'];
}

function normalize_theme(string $theme): string
{
    return in_array($theme, get_allowed_themes(), true) ? $theme : 'light';
}

function get_active_theme(): string
{
    try {
        $pdo = get_db();
        $stmt = $pdo->query("
            SELECT setting_value
            FROM site_settings
            WHERE setting_key = 'active_theme'
            LIMIT 1
        ");
        $row = $stmt->fetch();

        return normalize_theme($row ? (string) $row['setting_value'] : 'light');
    } catch (PDOException $e) {
        return 'light';
    }
}

function set_active_theme(string $theme): bool
{
    $theme = normalize_theme($theme);

    try {
        $pdo = get_db();

        $check = $pdo->prepare("
            SELECT COUNT(*)
            FROM site_settings
            WHERE setting_key = :setting_key
        ");
        $check->execute([':setting_key' => 'active_theme']);
        $exists = (int) $check->fetchColumn() > 0;

        if ($exists) {
            $stmt = $pdo->prepare("
                UPDATE site_settings
                SET setting_value = :theme
                WHERE setting_key = :setting_key
            ");

            return $stmt->execute([
                ':theme' => $theme,
                ':setting_key' => 'active_theme',
            ]);
        }

        $stmt = $pdo->prepare("
            INSERT INTO site_settings (setting_key, setting_value)
            VALUES (:setting_key, :theme)
        ");

        return $stmt->execute([
            ':setting_key' => 'active_theme',
            ':theme' => $theme,
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

function get_theme_cart_image(): string
{
    return match (get_active_theme()) {
        'dark' => 'Dark_Mode_Cart.png',
        'holiday' => 'Holiday_Mode_Cart.png',
        default => 'Light_Mode_Cart.png',
    };
}

function get_product_image_map(): array
{
    return [
        'external-hdd.jpg' => 'HDD.jpg',
        'raspberry-pi-ssd.jpg' => 'SSD.jpg',
        'computer-mouse.jpg' => 'Mouse.jpg',
        'headphones.jpg' => 'Headphones.jpg',
        'gaming-headset.jpg' => 'Gaming_Headset.jpg',
        'microphone.jpg' => 'Microphone.jpg',
        'webcam.jpg' => 'Camera.jpg',
        'ethernet-cable.jpg' => 'Ethernet_cable.jpg',
        'usb-thumb-drive.jpg' => 'USB-thumb-drive.jpg',
        'micro-sd-card.jpg' => 'MicroSD_card.jpg',
        'usb-a-cable.jpg' => 'usb-a-cable.jpg',
        'usb-b-cable.jpg' => 'USB_Mini-B_and_Standard-A_plugs.jpg',
        'usb-c-cable.jpg' => 'USB_Type-C_Cable.jpg',
        'usb-c-hub.jpg' => 'USB-C_Hub.jpg',
        'usb-c-charging-brick.jpg' => 'Blue_Charger.png',
        'usb-a-charging-brick.jpg' => 'Chargers.webp',
        'usb-c-power-bank.jpg' => 'usb-c-power-bank.jpg',
        'usb-c-extension-cable.jpg' => 'usb-c-extension-cable.jpg',
        'controller.jpg' => 'Red_Controller.png',
        'laptop-stand.jpg' => 'laptop-stand.jpg',
    ];
}

function product_image_filename(?string $image_file): string
{
    $fallback = 'laptop-stand.jpg';
    $file = trim((string) $image_file);

    if ($file === '') {
        return $fallback;
    }

    $map = get_product_image_map();

    if (isset($map[$file])) {
        $file = $map[$file];
    }

    if (is_file(__DIR__ . '/images/' . $file)) {
        return $file;
    }

    $case_insensitive_files = [
        'blue_charger.png' => 'Blue_Charger.png',
        'camera.jpg' => 'Camera.jpg',
        'chargers.webp' => 'Chargers.webp',
        'ethernet_cable.jpg' => 'Ethernet_cable.jpg',
        'gaming_headset.jpg' => 'Gaming_Headset.jpg',
        'gaming_mouse.jpg' => 'Gaming_Mouse.jpg',
        'hdd.jpg' => 'HDD.jpg',
        'headphones.jpg' => 'Headphones.jpg',
        'microphone.jpg' => 'Microphone.jpg',
        'microsd_card.jpg' => 'MicroSD_card.jpg',
        'mouse.jpg' => 'Mouse.jpg',
        'red_controller.png' => 'Red_Controller.png',
        'ssd.jpg' => 'SSD.jpg',
        'usb_mini-b_and_standard-a_plugs.jpg' => 'USB_Mini-B_and_Standard-A_plugs.jpg',
        'usb_type-c_cable.jpg' => 'USB_Type-C_Cable.jpg',
        'usb-a-cable.jpg' => 'usb-a-cable.jpg',
        'usb-c_hub.jpg' => 'USB-C_Hub.jpg',
        'usb-c-extension-cable.jpg' => 'usb-c-extension-cable.jpg',
        'usb-c-power-bank.jpg' => 'usb-c-power-bank.jpg',
        'usb-thumb-drive.jpg' => 'USB-thumb-drive.jpg',
    ];

    $lower = strtolower($file);

    if (isset($case_insensitive_files[$lower]) && is_file(__DIR__ . '/images/' . $case_insensitive_files[$lower])) {
        return $case_insensitive_files[$lower];
    }

    return $fallback;
}

function h($str): string
{
    return htmlspecialchars((string) $str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0;
}

function current_user_id(): int
{
    return (int) ($_SESSION['user_id'] ?? 0);
}

function current_username(): string
{
    return (string) ($_SESSION['username'] ?? '');
}

function current_user_role(): string
{
    return (string) ($_SESSION['user_role'] ?? 'guest');
}

function is_admin(): bool
{
    return current_user_role() === 'admin';
}

function login_user(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['username'] = (string) $user['username'];
    $_SESSION['full_name'] = (string) ($user['full_name'] ?? '');
    $_SESSION['email'] = (string) ($user['email'] ?? '');
    $_SESSION['user_role'] = (string) ($user['role'] ?? 'customer');
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function project_base_path(): string
{
    $path = parse_url(BASE_URL, PHP_URL_PATH) ?? '/';
    return rtrim($path, '/');
}

function current_request_uri(): string
{
    return $_SERVER['REQUEST_URI'] ?? (project_base_path() . '/');
}

function is_safe_redirect(string $redirect): bool
{
    if ($redirect === '') {
        return false;
    }

    $base = project_base_path();

    return str_starts_with($redirect, $base . '/')
        || $redirect === $base
        || $redirect === $base . '/';
}

function get_redirect_target(string $fallback = ''): string
{
    $redirect = trim((string) ($_GET['redirect'] ?? $_POST['redirect'] ?? ''));

    if (is_safe_redirect($redirect)) {
        return $redirect;
    }

    return $fallback !== '' ? $fallback : (BASE_URL . 'index.php');
}

function redirect_to(string $target): void
{
    header('Location: ' . $target);
    exit;
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to(BASE_URL . 'pages/login.php?redirect=' . urlencode(current_request_uri()));
    }
}

function require_admin(): void
{
    if (!is_logged_in()) {
        redirect_to(BASE_URL . 'pages/login.php?redirect=' . urlencode(current_request_uri()));
    }

    if (!is_admin()) {
        redirect_to(BASE_URL . 'index.php');
    }
}