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
    <style>
        button {
            margin-top: 15px;
            padding: 10px;
            background-color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.15s ease;
        }

        input {
            float: right;
        }

        button:hover {
            background: #4f0910;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>

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
        <div class="introText" style="text-align: center;">
            <h2>Contact Us!</h2>
            <form style="display: inline-block; text-align: left;">
                <label class="intro" for="name">Name:</label>
                <input type="text" id="name" required>
                </br></br>
                <label class="intro" for="email">Email:</label>
                <input type="email" id="email" required>
                </br></br>
                <label class="intro" for="subject">Subject:</label>
                <input type="text" id="subject" required>
                </br></br>
                <label class="intro" for="message">Message:</label>
                <textarea id="message" rows="5" required></textarea>
                </br></br>
                <button class="intro" type="submit" style="color: #fff; display: block; margin: 0 auto;">Send Message</button>
            </form>
        </div>
    </div>

</body>
</html>