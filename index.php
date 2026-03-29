<?php
// Load the shared configuration file
require_once __DIR__ . '/backend/config.php';

// Get the active site theme from the database
$theme = get_active_theme();
// Get the correct cart icon based on the active theme
$cart_image = get_theme_cart_image();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic page information -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title><?= h(SITE_NAME) ?></title>
    <!-- SEO meta tags -->
    <meta name="description" content="Main page for The Computer Store.">
    <meta name="keywords" content="Main Page, PC Products, Gaming Products">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <!-- Load the currently active theme stylesheet -->
    <link rel="stylesheet" href="styles/<?= h($theme) ?>.css">
</head>
<body class="theme-<?= h($theme) ?>">
    <!-- Main site navigation -->
    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                 <!-- Site logo and title -->
                <a href="index.php" class="banner whoosh-link">
                    <img src="images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
                </a>

                <ul class="navList">
                    <!-- Main navigation links -->
                    <li><a href="backend/products.php" class="whoosh-link">Products</a></li>
                    <li><a href="pages/About.php">About</a></li>
                    <li><a href="pages/contactUs.php">Contact Us</a></li>
                    <li><a href="pages/Wiki.html">Wiki</a></li>
                </ul>

                <ul class="login">
                    <!-- Login and register links -->
                    <li class="divider"></li>
                    <!-- Logged-in users see logout -->
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="backend/admin/products.php" class="whoosh-link">Admin</a></li>
                        <?php endif; ?>
                        <li><a href="pages/logout.php">Logout</a></li>
                    <!-- Guests see login and register -->
                    <?php else: ?>
                        <li><a href="pages/login.php">Login</a></li>
                        <li><a href="pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
                <!-- Shopping cart icon -->        
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
        <!-- Intro section -->
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
            <!-- Featured products section -->
            <h1>Featured Products:</h1>
            <div class="featuredProducts">
                <!-- Featured product card -->
                <a href="backend/product-detail.php?id=2" class="whoosh-link">
                    <img src="backend/images/SSD.jpg" alt="Raspberry Pi SSD" height="60">Raspberry Pi SSD
                </a>
                <a href="backend/product-detail.php?id=3" class="whoosh-link">
                    <img src="backend/images/Gaming_Mouse.jpg" alt="Gaming Mouse" height="60">Gaming Mouse
                </a>
                <a href="backend/product-detail.php?id=17" class="whoosh-link">
                    <img src="backend/images/Blue_Charger.png" alt="USB-C Power Bank" height="60">USB-C Power Bank
                </a>
                <a href="backend/product-detail.php?id=13" class="whoosh-link">
                    <img src="backend/images/USB_Type-C_Cable.jpg" alt="USB Type-C Cable" height="60">USB Type-C Cable
                </a>
                <a href="backend/product-detail.php?id=5" class="whoosh-link">
                    <img src="backend/images/Gaming_Headset.jpg" alt="Gaming Headset" height="60">Gaming Headset
                </a>

                <p>Our 5 most popular products!</p>
            </div>
        </div>

        <div class="multimedia">
            <!-- Multimedia section -->
            <h1>Store Overview</h1>
            <div class="mediaVideo">
                <video controls width="100%">
                <!-- Multimedia video -->
                <source src="multimedia/promo.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main>
    <!-- Multimedia resources -->
    <audio id="whoosh" src="multimedia/whoosh.mp3" preload="auto"></audio>
    <audio id="bell" src="multimedia/bell.mp3" preload="auto"></audio>
    <script src="backend/assets/js/whoosh.js"></script>
</body>
</html>