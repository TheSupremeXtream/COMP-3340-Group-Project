<?php
require_once __DIR__ . '/../backend/config.php';

if (is_logged_in()) {
    redirect_to(is_admin() ? BASE_URL . 'backend/admin/products.php' : BASE_URL . 'index.php');
}

$theme = get_active_theme();
$cart_image = get_theme_cart_image();

$full_name = '';
$username = '';
$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($full_name === '' || $username === '' || $email === '' || $password === '' || $confirm_password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters long.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = get_db();

            $check = $pdo->prepare("
                SELECT id
                FROM users
                WHERE username = :username OR email = :email
                LIMIT 1
            ");
            $check->execute([
                ':username' => $username,
                ':email' => $email,
            ]);

            if ($check->fetch()) {
                $error = 'That username or email is already in use.';
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, full_name, role, is_active)
                    VALUES (:username, :email, :password, :full_name, 'customer', 1)
                ");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':full_name' => $full_name,
                ]);

                redirect_to(BASE_URL . 'pages/login.php?registered=1');
            }
        } catch (PDOException $e) {
            $error = 'Unable to create account right now: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Create a new user account on The Computer Store.">
    <meta name="keywords" content="Create Account, Register Account, Accounts">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/forms.css">
</head>
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
            <h1>Register Account</h1>
            <p class="intro simpleFormIntro">Create your account to use more features of the store.</p>

            <?php if ($error !== ''): ?>
                <div class="simpleFormAlert error"><?= h($error) ?></div>
            <?php endif; ?>

            <form method="post" action="register.php" class="simpleForm">
                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="fullname">Full Name:</label>
                    <input class="simpleFormInput" type="text" id="fullname" name="fullname" value="<?= h($full_name) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="username">Username:</label>
                    <input class="simpleFormInput" type="text" id="username" name="username" value="<?= h($username) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="email">Email:</label>
                    <input class="simpleFormInput" type="email" id="email" name="email" value="<?= h($email) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="password">Password:</label>
                    <input class="simpleFormInput" type="password" id="password" name="password" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="confirm_password">Confirm Password:</label>
                    <input class="simpleFormInput" type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button class="simpleFormButton" type="submit">Create Account</button>
            </form>

            <p class="simpleFormHelp">
                Already have an account? <a href="login.php">Log in here</a>.
            </p>
        </div>
    </div>

</body>
</html>