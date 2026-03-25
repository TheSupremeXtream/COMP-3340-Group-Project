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
    <title>About — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn more about The Computer Store and what we offer.">
    <meta name="keywords" content="About Us, About, PC Store, Gaming Accessories">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
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

    <!-- Description of business case -->
    <div class="containerIntro">
        <div class="introText">
            <h1>Welcome to <?= h(SITE_NAME) ?>!</h1>
            <p class="intro">
                <?= h(SITE_NAME) ?> is your place to explore useful computer products and accessories.
            </p>
            <br>
            <p class="intro">
                Our online store offers a wide variety of items ranging from gaming gear and everyday accessories
                to practical computer parts. Customers can browse products such as mice, headsets, controllers,
                charging accessories, Ethernet cables, storage devices, and more.
            </p>
            <br>
            <p class="intro">
                Our goal is to provide a clean and user-friendly platform where shoppers can easily browse the
                catalogue, view product details, and explore different theme styles across the site.
            </p>
            <br>
            <p class="intro">
                Whether you are a gamer, student, or general computer user, <?= h(SITE_NAME) ?> is designed to be
                a convenient hub for everyday tech essentials.
            </p>
        </div>
    </div>

</body>
</html>