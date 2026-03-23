<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'store_db');

function h($str): string
{
    return htmlspecialchars((string) $str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function connect_server(): PDO
{
    return new PDO(
        'mysql:host=' . DB_HOST . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}

function connect_database(): PDO
{
    return new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}

function table_exists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM information_schema.tables
        WHERE table_schema = :db_name
          AND table_name = :table_name
    ");
    $stmt->execute([
        ':db_name' => DB_NAME,
        ':table_name' => $table,
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function ensure_theme_setting(PDO $pdo): void
{
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM site_settings
        WHERE setting_key = :setting_key
    ");
    $stmt->execute([':setting_key' => 'active_theme']);
    $exists = (int) $stmt->fetchColumn() > 0;

    if ($exists) {
        $update = $pdo->prepare("
            UPDATE site_settings
            SET setting_value = 'light'
            WHERE setting_key = 'active_theme'
              AND setting_value NOT IN ('light', 'dark', 'holiday')
        ");
        $update->execute();
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO site_settings (setting_key, setting_value)
        VALUES (:setting_key, :setting_value)
    ");
    $stmt->execute([
        ':setting_key' => 'active_theme',
        ':setting_value' => 'light',
    ]);
}

function import_sql_file(PDO $pdo, string $file_path): void
{
    if (!file_exists($file_path)) {
        throw new RuntimeException('database.sql file was not found.');
    }

    $lines = file($file_path);
    $statement = '';

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || str_starts_with($trimmed, '--')) {
            continue;
        }

        $statement .= $line;

        if (preg_match('/;\s*$/', $trimmed)) {
            $pdo->exec($statement);
            $statement = '';
        }
    }

    if (trim($statement) !== '') {
        $pdo->exec($statement);
    }
}

$status = '';
$error = '';

try {
    $server_pdo = connect_server();
    $server_pdo->exec("
        CREATE DATABASE IF NOT EXISTS " . DB_NAME . "
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci
    ");

    $db_pdo = connect_database();

    $required_tables = ['categories', 'products', 'product_options', 'users', 'orders', 'order_items', 'site_settings', 'service_log'];
    $already_installed = true;

    foreach ($required_tables as $table) {
        if (!table_exists($db_pdo, $table)) {
            $already_installed = false;
            break;
        }
    }

    if (!$already_installed) {
        import_sql_file($db_pdo, __DIR__ . '/database.sql');
        $db_pdo = connect_database();
    }

    if (table_exists($db_pdo, 'site_settings')) {
        ensure_theme_setting($db_pdo);
    }

    $status = $already_installed
        ? 'Database already exists and is ready to use.'
        : 'Database import completed successfully.';
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Setup</title>
    <link rel="stylesheet" href="../styles/light.css">
    <link rel="stylesheet" href="../styles/install.css">
</head>
<body>
    <div class="installWrap">
        <h1>Project Installer</h1>
        <p>This page creates the project database and makes sure the required tables and theme setting exist.</p>

        <?php if ($status !== ''): ?>
            <div class="installMsg"><?= h($status) ?></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="installErr"><?= h($error) ?></div>
        <?php endif; ?>

        <div class="installActions">
            <a href="../index.php" class="installBtn">Go to Home Page</a>
            <a href="admin/theme-settings.php" class="installBtn">Go to Theme Manager</a>
        </div>

        <p class="installNote">Run this once on a new setup. After that, you can keep it out of the way or remove it before final submission.</p>
    </div>
</body>
</html>