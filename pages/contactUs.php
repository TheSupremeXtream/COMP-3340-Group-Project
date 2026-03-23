<?php
require_once __DIR__ . '/../backend/config.php';

$theme = get_active_theme();
$cart_image = get_theme_cart_image();

$name = '';
$email = '';
$subject = '';
$message_text = '';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message_text === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (mb_strlen($name) > 120) {
        $error = 'Name is too long.';
    } elseif (mb_strlen($subject) > 150) {
        $error = 'Subject is too long.';
    } elseif (mb_strlen($message_text) < 10) {
        $error = 'Message must be at least 10 characters long.';
    } else {
        $success = 'Your message has been submitted successfully for this project demo.';
        $name = '';
        $email = '';
        $subject = '';
        $message_text = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — <?= h(SITE_NAME) ?></title>
    <meta name="description" content="Contact The Computer Store for support or questions.">
    <meta name="keywords" content="Contact Support, Help, Get In Touch">
    <meta name="authors" content="Ronit Mahajan, Shameer Sheikh, Raphael Ceradoy, David Woo">
    <link rel="stylesheet" href="../styles/<?= h($theme) ?>.css">
    <link rel="stylesheet" href="../styles/forms.css">
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

    <div class="containerIntro">
        <div class="introText simpleFormWrap">
            <h1>Contact Us</h1>
            <p class="intro simpleFormIntro">
                Send us a message if you have questions about products, orders, or the website.
            </p>

            <?php if ($error !== ''): ?>
                <div class="simpleFormAlert error"><?= h($error) ?></div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="simpleFormAlert success"><?= h($success) ?></div>
            <?php endif; ?>

            <form method="post" action="contactUs.php" class="simpleForm">
                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="name">Name:</label>
                    <input class="simpleFormInput" type="text" id="name" name="name" value="<?= h($name) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="email">Email:</label>
                    <input class="simpleFormInput" type="email" id="email" name="email" value="<?= h($email) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="subject">Subject:</label>
                    <input class="simpleFormInput" type="text" id="subject" name="subject" value="<?= h($subject) ?>" required>
                </div>

                <div class="simpleFormRow">
                    <label class="simpleFormLabel" for="message">Message:</label>
                    <textarea class="simpleFormTextarea" id="message" name="message" rows="5" required><?= h($message_text) ?></textarea>
                </div>

                <button class="simpleFormButton" type="submit">Send Message</button>
            </form>
        </div>
    </div>

</body>
</html>