<?php
require_once __DIR__ . '/../backend/config.php';

if (is_logged_in()) {
    redirect_to(is_admin() ? BASE_URL . 'backend/admin/products.php' : BASE_URL . 'index.php');
}

$theme = get_active_theme();
$cart_image = get_theme_cart_image();

$username = '';
$error = '';
$success = '';

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success = 'Account created successfully. You can now log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Form input validation
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $pdo = get_db();

            // Retrieve user account details
            $stmt = $pdo->prepare("
                SELECT id, username, full_name, email, password, role, is_active
                FROM users
                WHERE username = :username OR email = :email
                LIMIT 1
            ");
            $stmt->execute([
                ':username' => $username,
                ':email' => $username,
            ]);

            $user = $stmt->fetch();

            // Check for valid username and password
            if (!$user || !password_verify($password, $user['password'])) {
                $error = 'Invalid username/email or password.';
            } elseif ((int) $user['is_active'] !== 1) {
                $error = 'This account has been disabled. Please contact an administrator.';
            } else {
                login_user($user);

                $default_target = is_admin()
                    ? BASE_URL . 'backend/admin/products.php'
                    : BASE_URL . 'index.php';

                redirect_to(get_redirect_target($default_target));
            }
        } catch (PDOException $e) {
            $error = 'Unable to log in right now: ' . $e->getMessage();
        }
    }
}

$redirect_value = trim((string) ($_GET['redirect'] ?? $_POST['redirect'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Login page for user accounts on The Computer Store.">
    <meta name="keywords" content="Accounts, Login">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/forms.css">
</head>

<!-- Nagivation bar -->
<body class="theme-<?= h($theme) ?>">
    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                <a href="../index.php" class="banner">
                    <img src="../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
                </a>

                <ul class="navList">
                    <li><a href="../backend/products.php">Products</a></li>
                    <li><a href="About.php">About</a></li>
                    <li><a href="contactUs.php">Contact Us</a></li>
                    <li><a href="Wiki.html">Wiki</a></li>
                </ul>

                <ul class="login">
                    <li class="divider"></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>

                <ul class="cart">
                    <li class="divider"></li>
                    <li>
                        <a href="../cart.html">
                            <img src="../images/<?= h($cart_image) ?>" alt="Cart" height="60">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="containerIntro">
        <div class="introText simpleFormWrap">
            <h1>Login</h1>
            <p class="intro simpleFormIntro">Enter your account details to sign in.</p>

            <?php if ($error !== ''): ?>
                <div class="simpleFormAlert error"><?= h($error) ?></div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="simpleFormAlert success"><?= h($success) ?></div>
            <?php endif; ?>

            <!-- Simple login form -->
            <form method="post" action="login.php" class="simpleForm">
                <input type="hidden" name="redirect" value="<?= h($redirect_value) ?>">

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="username">Username or Email:</label>
                    <input class="simpleFormInput" type="text" id="username" name="username" value="<?= h($username) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="password">Password:</label>
                    <input class="simpleFormInput" type="password" id="password" name="password" required>
                </div>

                <button class="simpleFormButton" type="submit">Login</button>
            </form>

            <p class="simpleFormHelp">
                Need an account? <a href="register.php">Create one here</a>.
            </p>
        </div>
    </div>

</body>
</html>