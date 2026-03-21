<?php
require_once __DIR__ . '/backend/config.php';

$theme = get_active_theme();

$cart_image = match ($theme) {
    'dark' => 'Dark_Mode_Cart.png',
    'holiday' => 'Holiday_Mode_Cart.png',
    default => 'Light_Mode_Cart.png',
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Computer Store</title>
    <link rel="stylesheet" href="styles/<?= h($theme) ?>.css">
</head>
<body>
    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                <a href="index.php" class="banner">
                    <img src="images/logo.png" alt="The Computer Store" height="60">The Computer Store
                </a>
                <ul class="navList">
                    <li><a href="backend/products.php">Products</a></li>
                    <li><a href="pages/About.php">About</a></li>
                    <li><a href="pages/contactUs.php">Contact Us</a></li>
                    <li><a href="pages/Wiki.html">Wiki</a></li>
                </ul>
                <ul class="login">
                    <li class="divider"></li>
                    <li><a href="pages/login.html">Login</a></li>
                    <li><a href="pages/login.html">Register</a></li>
                </ul>
                <ul class="cart">
                    <li class="divider"></li>
                    <li><a href="cart.html">
                        <img src="images/Dark_Mode_Cart.png" alt="Cart" height="60">
                    </a></li>
                </ul>
                <ul class="toggle">
                    <li class="divider"></li>
                    <li><a href="backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="images/Sun.png" alt="Theme Manager" height="60">
                    </a></li>
                    <li><a href="backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="images/Moon.png" alt="Theme Manager" height="60">
                    </a></li>
                    <li><a href="backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="images/Egg.png" alt="Theme Manager" height="60">
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <main>

        <div class="containerIntro">
            <div class="introText">
                <h1>Welcome!</h1>
                <p class="intro">Welcome to the Computer Store, where the autumn awaits you! Browse our selection of cables, accesories, and gear at all affordable prices!</p>
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
                    <img src="backend/images/Blue_Charger.png" alt="Power Bank" height="60">Red Power Bank
                </a>
                <a href="backend/product-detail.php?id=13">
                    <img src="backend/images/USB_Type-C_Cable.jpg" alt="USB-C Cable" height="60">USB Type-C Cable
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
                    <source src="videos/main.mp4" type="video/mp4" />
                </video>
            </div>
        </div>
    </main>
</body>
</html>