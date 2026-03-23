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
    <title>Editing Products — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn how an administrator can add and edit products in the catalogue.">
    <meta name="keywords" content="Add Products, Edit Products, Product Catalog, Admin">
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
            <h1>How to Edit the Product Catalog</h1>
            <p class="intro">
                This page explains how an administrator can add new products or update existing product records.
            </p>
        </div>
    </div>

    <div class="featured">
        <h1>Steps</h1>

        <div class="wikiSection wikiContentWrap">
            <ul class="wikiSteps">
                <li>Log in using an <strong>admin account</strong>.</li>
                <li>Open the admin area and go to the <strong>Products</strong> page.</li>
                <li>To add a new item, click <strong>+ Add Product</strong>.</li>
                <li>Enter the main product information, including category, title, brand, description, base price, stock, and image file.</li>
                <li>Choose whether the product should be marked as <strong>Featured</strong> and whether it should be <strong>Active</strong>.</li>
                <li>Enter at least <strong>two product options</strong> such as color, storage, connectivity, length, or wattage.</li>
                <li>Click <strong>Create Product</strong> to save a new item, or click <strong>Save Changes</strong> when editing an existing one.</li>
                <li>To update an existing item, go back to the Products page and click the <strong>Edit</strong> button beside that product.</li>
            </ul>

            <div class="wikiImageWrap">
                <img class="wikiImage" src="../images/addProductForm1.png" alt="Screenshot of the product form page" width="489" height="262">
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