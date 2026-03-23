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
    <title>Contacting Support — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Learn how to contact support through the store website.">
    <meta name="keywords" content="Customer Support, Contact Form, Help">
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
            <h1>How to Contact Support</h1>
            <p class="intro">
                This page explains how users can send questions or support requests through the Contact Us page.
            </p>
        </div>
    </div>

    <div class="featured">
        <h1>Steps</h1>

        <div class="wikiSection wikiContentWrap">
            <ul class="wikiSteps">
                <li>If the wiki pages have not answered your question, go to the <strong>Contact Us</strong> page from the site navigation.</li>
                <li>Enter the required information: <strong>Name</strong>, <strong>Email</strong>, <strong>Subject</strong>, and <strong>Message</strong>.</li>
                <li>Review your information to make sure everything is correct.</li>
                <li>Click the <strong>Send Message</strong> button to submit your request.</li>
                <li>Support requests can be used for questions about products, website issues, or general help.</li>
            </ul>

            <div class="wikiImageWrap">
                <img class="wikiImage" src="../images/contactUsPage.png" alt="Screenshot of the contact us page" width="501" height="245">
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