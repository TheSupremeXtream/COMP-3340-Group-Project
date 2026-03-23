<?php
require_once __DIR__ . '/backend/config.php';

$theme = get_active_theme();
$cart_image = get_theme_cart_image();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(SITE_NAME) ?></title>
    <meta name="description" content="Main page for The Computer Store.">
    <meta name="keywords" content="Main Page, PC Products, Gaming Products">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="styles/<?= h($theme) ?>.css">
</head>
<body class="theme-<?= h($theme) ?>">
    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                <a href="index.php" class="banner">
                    <img src="images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
                </a>

                <ul class="navList">
                    <li><a href="backend/products.php">Products</a></li>
                    <li><a href="pages/About.php">About</a></li>
                    <li><a href="pages/contactUs.php">Contact Us</a></li>
                    <li><a href="pages/Wiki.html">Wiki</a></li>
                </ul>

                <ul class="login">
                    <li class="divider"></li>

                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="backend/admin/products.php">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="pages/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="pages/login.php">Login</a></li>
                        <li><a href="pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>

                <ul class="cart">
                    <li class="divider"></li>
                    <li>
                        <a href="cart.html">
                            <img src="images/<?= h($cart_image) ?>" alt="Cart" height="60">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <main>
        <div class="containerIntro">
            <div class="introText">
                <h1>Welcome!</h1>
                <p class="intro">
                    Welcome to The Computer Store. Browse our selection of cables, accessories, storage, audio gear,
                    and other computer essentials at affordable prices.
                </p>
            </div>
        </div>

        <div class="featured">
            <h1>Featured Products:</h1>
            <div class="featuredProducts">
                <a href="backend/product-detail.php?id=2">
                    <img src="backend/images/SSD.jpg" alt="Raspberry Pi SSD" height="60">Raspberry Pi SSD
                </a>
                <a href="backend/product-detail.php?id=3">
                    <img src="backend/images/Gaming_Mouse.jpg" alt="Gaming Mouse" height="60">Gaming Mouse
                </a>
                <a href="backend/product-detail.php?id=15">
                    <img src="backend/images/Blue_Charger.png" alt="USB-C Charging Brick" height="60">USB-C Charging Brick
                </a>
                <a href="backend/product-detail.php?id=13">
                    <img src="backend/images/USB_Type-C_Cable.jpg" alt="USB Type-C Cable" height="60">USB Type-C Cable
                </a>
                <a href="backend/product-detail.php?id=5">
                    <img src="backend/images/Gaming_Headset.jpg" alt="Gaming Headset" height="60">Gaming Headset
                </a>

                <p>Our 5 most popular products!</p>
            </div>
        </div>

        <div class="multimedia">
            <h1>Store Overview</h1>
            <div class="mediaVideo">
                <video controls width="100%">
                    <source src="videos/main.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main>
</body>
</html>