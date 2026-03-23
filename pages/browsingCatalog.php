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
    <title>Browsing the Catalog — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn how to browse the store catalogue and view product details.">
    <meta name="keywords" content="Browsing the Catalog, Viewing Product Details, Product Search">
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
            <h1>How to Browse the Catalog</h1>
            <p class="intro">
                This page explains how users can search the catalogue, filter products, and open product details.
            </p>
        </div>
    </div>

    <div class="featured">
        <h1>Steps</h1>

        <div class="wikiSection wikiContentWrap">
            <ol class="wikiSteps">
                <li>From the home page, click <strong>Products</strong> in the navigation bar or choose one of the featured products.</li>
                <li>On the Products page, use the <strong>search box</strong> to search by product name or brand.</li>
                <li>Use the <strong>category filter</strong> to narrow the results to a specific type of product.</li>
                <li>Use the <strong>sort menu</strong> to sort products by name or price.</li>
                <li>Click <strong>View Details</strong> on any product card to open the full product page.</li>
                <li>On the product detail page, users can review the description, select available options, adjust quantity, and view the total price.</li>
            </ol>

            <div class="wikiImageWrap">
                <img class="wikiImage" src="../images/productsPage.png" alt="Screenshot of the products page" width="639" height="303">
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