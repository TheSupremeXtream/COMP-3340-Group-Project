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
    <title>Help Wiki — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Navigation page for the help wiki and support resources.">
    <meta name="keywords" content="Help Wiki, FAQ, support, how to use website">
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
        <div class="introText wikiIntroCenter">
            <h1>Help Wiki</h1>
            <p class="intro">Having trouble? Here are some resources to help you use the website.</p>
        </div>
    </div>

    <div class="featured">
        <h1>Help Topics</h1>

        <div class="wikiSection">
            <ul class="wikiList">
                <li><a href="browsingCatalog.php">Browsing the Catalog</a></li>
                <li><a href="switchingThemes.php">Switching Themes<br>(Admin Only)</a></li>
                <li><a href="editingProducts.php">Editing the Product Catalog<br>(Admin Only)</a></li>
                <li><a href="userAdministration.php">Managing User Accounts<br>(Admin Only)</a></li>
                <li><a href="contactSupport.php">Contacting Support</a></li>
                <li><a href="creatingAccount.php">Creating an Account</a></li>
            </ul>
        </div>

        <div class="backButtonContainer">
            <a href="../index.php" class="backButton">&larr; Back to Home</a>
        </div>
    </div>

</body>
</html>