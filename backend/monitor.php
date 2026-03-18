<?php
require_once __DIR__ . '/config.php';

// if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: login.php?redirect=monitor.php');
//     exit;
// }

function check_database(): array {
    $start = microtime(true);
    try {
        get_db()->query('SELECT 1');
        $ms = (int) round((microtime(true) - $start) * 1000);
        return ['status' => $ms > 500 ? 'degraded' : 'online', 'response_ms' => $ms, 'detail' => "Query completed in {$ms}ms."];
    } catch (PDOException $e) {
        return ['status' => 'offline', 'response_ms' => 0, 'detail' => $e->getMessage()];
    }
}

function check_catalogue(): array {
    $start = microtime(true);
    try {
        $count = get_db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $ms    = (int) round((microtime(true) - $start) * 1000);
        return $count < 1
            ? ['status' => 'degraded', 'response_ms' => $ms, 'detail' => 'Products table is empty.']
            : ['status' => 'online',   'response_ms' => $ms, 'detail' => "{$count} product(s) found."];
    } catch (PDOException $e) {
        return ['status' => 'offline', 'response_ms' => 0, 'detail' => $e->getMessage()];
    }
}

function check_file_storage(): array {
    $start = microtime(true);
    $dir   = __DIR__ . '/images';
    $ms    = (int) round((microtime(true) - $start) * 1000);
    if (is_dir($dir) && is_readable($dir)) {
        $count = count(glob($dir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE));
        return ['status' => 'online', 'response_ms' => $ms, 'detail' => "{$count} image file(s) in storage."];
    }
    return ['status' => 'offline', 'response_ms' => $ms, 'detail' => "Directory not found: {$dir}"];
}

function check_session_service(): array {
    $start  = microtime(true);
    $status = session_status() === PHP_SESSION_ACTIVE ? 'online' : 'offline';
    $ms     = (int) round((microtime(true) - $start) * 1000);
    return ['status' => $status, 'response_ms' => $ms, 'detail' => $status === 'online' ? 'Session active (ID: ' . session_id() . ').' : 'PHP session is not active.'];
}

function check_auth_service(): array {
    $start = microtime(true);
    try {
        $count = get_db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $ms    = (int) round((microtime(true) - $start) * 1000);
        return ['status' => 'online', 'response_ms' => $ms, 'detail' => "{$count} registered user(s)."];
    } catch (PDOException $e) {
        return ['status' => 'offline', 'response_ms' => 0, 'detail' => $e->getMessage()];
    }
}

function check_orders_service(): array {
    $start = microtime(true);
    try {
        $count = get_db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
        $ms    = (int) round((microtime(true) - $start) * 1000);
        return ['status' => 'online', 'response_ms' => $ms, 'detail' => "{$count} order(s) in system."];
    } catch (PDOException $e) {
        return ['status' => 'offline', 'response_ms' => 0, 'detail' => $e->getMessage()];
    }
}

function check_email_service(): array {
    $start     = microtime(true);
    $ms        = (int) round((microtime(true) - $start) * 1000);
    $available = function_exists('mail');
    return ['status' => $available ? 'online' : 'offline', 'response_ms' => $ms, 'detail' => $available ? 'PHP mail() is available.' : 'PHP mail() is not available on this server.'];
}

function check_settings_service(): array {
    $start = microtime(true);
    try {
        $rows = get_db()->query('SELECT COUNT(*) FROM site_settings')->fetchColumn();
        $ms   = (int) round((microtime(true) - $start) * 1000);
        return ['status' => 'online', 'response_ms' => $ms, 'detail' => "{$rows} setting(s) loaded."];
    } catch (PDOException $e) {
        return ['status' => 'offline', 'response_ms' => 0, 'detail' => $e->getMessage()];
    }
}

$services = [
    'Database Connection'   => 'check_database',
    'Product Catalogue'     => 'check_catalogue',
    'File Storage'          => 'check_file_storage',
    'Session Service'       => 'check_session_service',
    'Authentication'        => 'check_auth_service',
    'Orders Subsystem'      => 'check_orders_service',
    'Email Service'         => 'check_email_service',
    'Site Settings / Theme' => 'check_settings_service',
];

$results      = [];
$any_offline  = false;
$any_degraded = false;

foreach ($services as $name => $fn) {
    $result         = $fn();
    $result['name'] = $name;
    $results[]      = $result;
    if ($result['status'] === 'offline') {
        $any_offline = true;
    }
    if ($result['status'] === 'degraded') {
        $any_degraded = true;
    }
    try {
        $stmt = get_db()->prepare('INSERT INTO service_log (service_name, status, response_ms, detail) VALUES (:name, :status, :ms, :detail)');
        $stmt->execute([
            ':name' => $name,
            ':status' => $result['status'],
            ':ms' => $result['response_ms'],
            ':detail' => $result['detail'],
        ]);
    } catch (PDOException $e) {
    }
}

$overall_status = 'All Systems Operational';
$overall_class  = 'status-all-good';
if ($any_offline) {
    $overall_status = 'Service Disruption Detected';
    $overall_class  = 'status-disruption';
} elseif ($any_degraded) {
    $overall_status = 'Partial Degradation';
    $overall_class  = 'status-degraded';
}

$log_rows = [];
try {
    $log_rows = get_db()->query('SELECT * FROM service_log ORDER BY checked_at DESC LIMIT 20')->fetchAll();
} catch (PDOException $e) {
}

$theme      = get_active_theme();
$check_time = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>System Monitor — The Computer Store</title>
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="monitor.css">
</head>
<body class="admin-monitor">

<header class="admin-header">
    <a href="../index.php" class="brand">⚡ The Computer Store — Admin</a>
    <nav class="admin-nav">
        <a href="admin/theme-settings.php">Templates</a>
        <a href="admin/products.php">Products</a>
        <a href="admin/users.php">Users</a>
        <a href="monitor.php" class="active">Monitor</a>
    </nav>
</header>

<main class="monitor-main">

    <div class="monitor-header">
        <div>
            <h1>System Status Monitor</h1>
            <p class="check-time">Last checked: <strong><?= h($check_time) ?></strong></p>
        </div>
        <form method="GET" action="monitor.php">
            <button type="submit" class="btn-refresh">🔄 Refresh Now</button>
        </form>
    </div>

    <div class="overall-status <?= h($overall_class) ?>">
        <?php if ($overall_class === 'status-all-good'): ?>
            <span class="status-icon">✅</span>
        <?php elseif ($overall_class === 'status-degraded'): ?>
            <span class="status-icon">⚠️</span>
        <?php else: ?>
            <span class="status-icon">🔴</span>
        <?php endif; ?>
        <span class="status-text"><?= h($overall_status) ?></span>
    </div>

    <section class="services-grid">
        <?php foreach ($results as $svc):
            $badge_class = match($svc['status']) {
                'online' => 'badge-online',
                'degraded' => 'badge-degraded',
                default => 'badge-offline'
            };
            $badge_label = match($svc['status']) {
                'online' => '● Online',
                'degraded' => '◐ Degraded',
                default => '○ Offline'
            };
        ?>
            <div class="service-card <?= h($badge_class) ?>">
                <div class="card-top">
                    <span class="service-name"><?= h($svc['name']) ?></span>
                    <span class="status-badge <?= h($badge_class) ?>"><?= h($badge_label) ?></span>
                </div>
                <div class="card-detail"><?= h($svc['detail']) ?></div>
                <div class="card-meta">Response: <strong><?= $svc['response_ms'] ?>ms</strong></div>
            </div>
        <?php endforeach; ?>
    </section>

    <?php
    $count_online   = count(array_filter($results, fn($r) => $r['status'] === 'online'));
    $count_degraded = count(array_filter($results, fn($r) => $r['status'] === 'degraded'));
    $count_offline  = count(array_filter($results, fn($r) => $r['status'] === 'offline'));
    $total          = count($results);
    $uptime_pct     = $total > 0 ? round(($count_online / $total) * 100) : 0;
    ?>
    <section class="stats-row">
        <div class="stat-box online"><div class="stat-num"><?= $count_online ?></div><div class="stat-label">Online</div></div>
        <div class="stat-box degraded"><div class="stat-num"><?= $count_degraded ?></div><div class="stat-label">Degraded</div></div>
        <div class="stat-box offline"><div class="stat-num"><?= $count_offline ?></div><div class="stat-label">Offline</div></div>
        <div class="stat-box uptime"><div class="stat-num"><?= $uptime_pct ?>%</div><div class="stat-label">Healthy</div></div>
    </section>

    <section class="history-section">
        <h2>Recent Check History (last 20)</h2>
        <?php if (empty($log_rows)): ?>
            <p class="no-history">No history yet — run a check to populate.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table class="history-table">
                    <thead>
                        <tr><th>#</th><th>Service</th><th>Status</th><th>Response (ms)</th><th>Detail</th><th>Checked At</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($log_rows as $row): ?>
                        <tr class="row-<?= h($row['status']) ?>">
                            <td><?= (int) $row['id'] ?></td>
                            <td><?= h($row['service_name']) ?></td>
                            <td><span class="status-badge badge-<?= h($row['status']) ?>"><?= h(ucfirst($row['status'])) ?></span></td>
                            <td><?= (int) $row['response_ms'] ?></td>
                            <td><?= h($row['detail']) ?></td>
                            <td><?= h($row['checked_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

</main>

<footer class="admin-footer">
    <p>&copy; <?= date('Y') ?> The Computer Store — Admin Panel</p>
</footer>

<script>
    let countdown = 60;
    const timerEl = document.createElement('p');
    timerEl.style.cssText = 'text-align:center;color:#888;font-size:.85rem;margin:1rem 0';
    document.querySelector('.monitor-header').appendChild(timerEl);
    const tick = setInterval(() => {
        countdown--;
        timerEl.textContent = `Auto-refreshing in ${countdown}s…`;
        if (countdown <= 0) {
            clearInterval(tick);
            window.location.reload();
        }
    }, 1000);
</script>

</body>
</html>