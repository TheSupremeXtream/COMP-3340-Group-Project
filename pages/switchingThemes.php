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
    <title>Switching Themes — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn how an admin can switch the site theme using the template manager.">
    <meta name="keywords" content="Switching Site Themes, Light Mode, Dark Mode, Holiday Theme, Admin">
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
            <h1>How to Switch Site Themes</h1>
            <p class="intro">
                This page explains how an administrator can change the active site theme using the template manager.
            </p>
        </div>
    </div>

    <div class="featured">
        <h1>Steps</h1>

        <div class="wikiSection wikiContentWrap">
            <ul class="wikiSteps">
                <li>Log in using an <strong>admin account</strong>.</li>
                <li>Open the admin area and go to the <strong>Templates</strong> page.</li>
                <li>The page shows the available site themes: <strong>Light</strong>, <strong>Dark</strong>, and <strong>Holiday</strong>.</li>
                <li>The currently active theme is clearly marked on the page.</li>
                <li>Click the <strong>Activate Theme</strong> button on the theme you want to use.</li>
                <li>Once selected, that theme becomes the active site-wide theme for the store.</li>
            </ul>

            <div class="wikiImageWrap">
                <img class="wikiImage" src="../images/templatesMenu.png" alt="Screenshot of the template manager page" width="500" height="176">
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