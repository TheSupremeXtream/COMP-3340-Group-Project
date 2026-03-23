<?php
require_once __DIR__ . '/../backend/config.php';

$theme = get_active_theme();
$cart_image = get_theme_cart_image();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating an Account — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn how to create a user account on the store website.">
    <meta name="keywords" content="Registering Account, Account Creation, User Signup">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/wiki.css">
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
                    <li><a href="Wiki.php">Wiki</a></li>
                </ul>

                <ul class="login">
                    <li class="divider"></li>

                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="../backend/admin/products.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
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
        <div class="introText">
            <h1>How to Create an Account</h1>
            <p class="intro">
                This page explains how users can register a new account on the website.
            </p>
        </div>
    </div>

    <div class="featured">
        <h1>Steps</h1>

        <div class="wikiSection wikiContentWrap">
            <ul class="wikiSteps">
                <li>Click the <strong>Register</strong> link in the top-right area of the navigation bar.</li>
                <li>On the registration page, enter the required details: <strong>Full Name</strong>, <strong>Username</strong>, <strong>Email</strong>, and <strong>Password</strong>.</li>
                <li>Make sure the information is typed correctly, especially your username and email address.</li>
                <li>Click the <strong>Create Account</strong> button to submit the registration form.</li>
                <li>After creating an account, users can return later and use the login page to sign in.</li>
            </ul>

            <div class="wikiImageWrap">
                <img class="wikiImage" src="../images/registerAccountForm.png" alt="Screenshot of the register account page" width="500" height="221">
            </div>
        </div>

        <div class="backButtonContainer">
            <a href="Wiki.php" class="backButton">&larr; Back to Wiki</a>
        </div>

        <div class="backButtonContainer">
            <a href="../index.php" class="backButton">&larr; Back to Home</a>
        </div>
    </div>

</body>
</html>