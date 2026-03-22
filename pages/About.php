<?php
require_once __DIR__ . '/../backend/config.php';

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
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
</head>
<body>
    <div class="container">
        <div class="navOuter">
            <div class="navInner">
                <a href="../index.php" class="banner">
                    <img src="../images/logo.png" alt="The Computer Store" height="60">The Computer Store
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
                    <li><a href="cart.html">
                        <img src="../images/Dark_Mode_Cart.png" alt="Cart" height="60">
                    </a></li>
                </ul>
                <ul class="toggle">
                    <li class="divider"></li>
                    <li><a href="../backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="../images/Sun.png" alt="Theme Manager" height="60">
                    </a></li>
                    <li><a href="../backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="../images/Moon.png" alt="Theme Manager" height="60">
                    </a></li>
                    <li><a href="../backend/admin/theme-settings.php" title="Theme Manager">
                        <img src="../images/Egg.png" alt="Theme Manager" height="60">
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="containerIntro">
        <div class="introText">
            <h1>Welcome to The Computer Store!</h1>
            <p class="intro">The Computer Store is your number 1 place to enhance your PC gaming experience.</p>
            <p class="intro">Our online store offers a wide variety of products from PC gaming gear to PC parts.</br>
            Our goal is to provide users with a user-friendly platform where they can explore items</br>
            such as gaming mice, headsets, controllers, and other accessories, as well as PC parts</br>
            like Ethernet cables, external storage devices, and more!
            </p>
            <p class="intro">Our store serves as a convenient hub for gamers, students, and computer users alike.</br>
            Whatever you might be looking for, I guarantee that our store has what you are looking for! 
            </p>
        </div>
    </div>

</body>
</html>