<?php
require_once __DIR__ . '/../config.php';

require_admin();

$theme = get_active_theme();
$search = trim($_GET['q'] ?? '');
$message = '';
$message_class = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int) ($_POST['user_id'] ?? 0);
    $action = trim($_POST['action'] ?? '');

    if ($user_id <= 0 || !in_array($action, ['enable', 'disable'], true)) {
        $message = 'Invalid user action.';
        $message_class = 'error';
    } else {
        try {
            $pdo = get_db();

            $stmt = $pdo->prepare("
                SELECT id, username, email, role, is_active
                FROM users
                WHERE id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $user_id]);
            $user_row = $stmt->fetch();

            if (!$user_row) {
                $message = 'User account not found.';
                $message_class = 'error';
            } elseif ($action === 'disable' && $user_row['role'] === 'admin') {
                $message = 'Admin accounts cannot be disabled from this page.';
                $message_class = 'error';
            } else {
                $new_status = $action === 'enable' ? 1 : 0;

                $stmt = $pdo->prepare("
                    UPDATE users
                    SET is_active = :is_active
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':is_active' => $new_status,
                    ':id' => $user_id,
                ]);

                $message = $new_status === 1
                    ? 'User account enabled successfully.'
                    : 'User account disabled successfully.';
            }
        } catch (PDOException $e) {
            $message = 'Unable to update user account: ' . $e->getMessage();
            $message_class = 'error';
        }
    }
}

try {
    $pdo = get_db();

    $summary = $pdo->query("
        SELECT
            COUNT(*) AS total_users,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_users,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS inactive_users,
            SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS admin_users
        FROM users
    ")->fetch();

    if ($search !== '') {
        $stmt = $pdo->prepare("
            SELECT id, username, email, full_name, role, is_active, created_at
            FROM users
            WHERE username LIKE :search
               OR email LIKE :search_email
               OR full_name LIKE :search_name
               OR role LIKE :search_role
            ORDER BY created_at DESC
        ");
        $stmt->execute([
            ':search' => "%{$search}%",
            ':search_email' => "%{$search}%",
            ':search_name' => "%{$search}%",
            ':search_role' => "%{$search}%",
        ]);
        $users = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query("
            SELECT id, username, email, full_name, role, is_active, created_at
            FROM users
            ORDER BY created_at DESC
        ");
        $users = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    $summary = [
        'total_users' => 0,
        'active_users' => 0,
        'inactive_users' => 0,
        'admin_users' => 0,
    ];
    $users = [];
    $message = 'Unable to load users: ' . $e->getMessage();
    $message_class = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>User Administration — <?= h(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../../styles/admin-users.css">
</head>
<body class="theme-<?= h($theme) ?>">

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../../index.php" class="banner">
                <img src="../../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
            </a>
            <ul class="navList">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../monitor.php">Monitor</a></li>
                <li><a href="theme-settings.php">Templates</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="../../pages/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<main>

    <div class="containerIntro">
        <div class="introText">
            <h1>User Administration</h1>
            <p class="intro">Use this page to review registered users and enable or disable customer accounts from the admin side of the website.</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alertBar <?= h($message_class) ?>"><?= h($message) ?></div>
        <?php endif; ?>

        <div class="adminToolbar">
            <form method="GET" action="users.php" class="adminSearchForm">
                <input
                    type="search"
                    name="q"
                    class="adminSearchInput"
                    placeholder="Search by username, email, full name, or role..."
                    value="<?= h($search) ?>"
                >
                <button type="submit" class="btnPrimary">Search</button>
                <?php if ($search !== ''): ?>
                    <a href="users.php" class="btnSecondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="summaryRow">
            <div class="summaryBox">
                <div class="summaryNum"><?= (int) $summary['total_users'] ?></div>
                <div class="summaryLabel">Total Users</div>
            </div>
            <div class="summaryBox">
                <div class="summaryNum"><?= (int) $summary['active_users'] ?></div>
                <div class="summaryLabel">Active Users</div>
            </div>
            <div class="summaryBox">
                <div class="summaryNum"><?= (int) $summary['inactive_users'] ?></div>
                <div class="summaryLabel">Inactive Users</div>
            </div>
            <div class="summaryBox">
                <div class="summaryNum"><?= (int) $summary['admin_users'] ?></div>
                <div class="summaryLabel">Admin Accounts</div>
            </div>
        </div>

        <p class="pageCount"><?= count($users) ?> user record(s) shown</p>
    </div>

    <div class="featured">
        <h1>User Records</h1>

        <div class="dataTableWrap">
            <table class="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= (int) $user['id'] ?></td>
                                <td><?= h($user['username']) ?></td>
                                <td><?= h($user['email']) ?></td>
                                <td><?= h($user['full_name'] ?: '—') ?></td>
                                <td>
                                    <span class="statusPill <?= $user['role'] === 'admin' ? 'admin' : 'customer' ?>">
                                        <?= h(ucfirst($user['role'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="statusPill <?= (int) $user['is_active'] === 1 ? 'active' : 'inactive' ?>">
                                        <?= (int) $user['is_active'] === 1 ? 'Active' : 'Disabled' ?>
                                    </span>
                                </td>
                                <td><?= h($user['created_at']) ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span class="statusPill admin">Protected</span>
                                    <?php else: ?>
                                        <form method="POST" action="users.php<?= $search !== '' ? '?q=' . urlencode($search) : '' ?>" class="actionForm">
                                            <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                            <?php if ((int) $user['is_active'] === 1): ?>
                                                <input type="hidden" name="action" value="disable">
                                                <button type="submit" class="btnSmall">Disable</button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="enable">
                                                <button type="submit" class="btnSmall">Enable</button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>