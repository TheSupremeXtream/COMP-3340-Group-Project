<?php
/* Shameer Sheikh worked on the frontend admin interface parts in this file. */
require_once __DIR__ . '/../config.php';

require_admin();

/* Theme options shown in the admin template manager */
$themes = [
    'light' => [
        'label' => 'Light Theme',
        'description' => 'Default bright layout with the original store look and red accents.',
        'switch' => 'switch-light',
    ],
    'dark' => [
        'label' => 'Dark Theme',
        'description' => 'Dark layout for a cooler look that still keeps the same site structure.',
        'switch' => 'switch-dark',
    ],
    'holiday' => [
        'label' => 'Holiday Theme',
        'description' => 'Festive green and red layout for seasonal promotions and special events.',
        'switch' => 'switch-holiday',
    ],
];

$message = '';
$message_class = 'success';

/* Handle admin theme selection form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_theme = trim($_POST['theme'] ?? '');

    if (!isset($themes[$selected_theme])) {
        $message = 'Invalid theme selected.';
        $message_class = 'error';
    } else {
        try {
            $saved = set_active_theme($selected_theme);

            if ($saved) {
                $message = $themes[$selected_theme]['label'] . ' is now active.';
            } else {
                $message = 'Unable to update the active theme. Check that store_db and site_settings exist.';
                $message_class = 'error';
            }
        } catch (Throwable $e) {
            $message = 'Error: ' . $e->getMessage();
            $message_class = 'error';
        }
    }
}

/* Read the currently active site theme */
$current_theme = get_active_theme();
$current_theme_label = $themes[$current_theme]['label'] ?? 'Light Theme';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Template Manager — <?= h(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../../styles/<?= h($current_theme) ?>.css">
    <link rel="stylesheet" href="../../styles/admin-theme.css">
</head>
<body class="theme-<?= h($current_theme) ?>">

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../../index.php" class="banner">
                <img src="../../images/logo.png" alt="<?= h(SITE_NAME) ?>" height="60"><?= h(SITE_NAME) ?>
            </a>
            <ul class="navList">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../monitor.php">Monitor</a></li>
                <li><a href="theme-settings.php">Templates</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="../../pages/adminDocumentation.html">Admin Docs</a></li>
                <li><a href="../../pages/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<main>

    <div class="containerIntro">
        <div class="introText">
            <h1>Site Template Manager</h1>
            <p class="intro">This admin page controls the active site-wide template. Choose one of the three templates below and the store will use that CSS file.</p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="alertBar <?= h($message_class) ?>"><?= h($message) ?></div>
        <?php endif; ?>

        <p class="currentTheme">Current template: <?= h($current_theme_label) ?></p>
        <p class="templateNote">Your existing <code>active_theme</code> database setting is being used for the site theme.</p>
        <p class="templateNote">
            <a href="../../pages/switchingThemes.html"><u>Theme Manager Help</u></a>
            &nbsp;|&nbsp;
            <a href="../../pages/adminDocumentation.html"><u>Full Admin Guide</u></a>
        </p>
    </div>

    <div class="featured">
        <h1>Available Templates</h1>

        <div class="themeGrid">
            <?php foreach ($themes as $key => $theme_item): ?>
                <form method="POST" class="themeCard <?= $current_theme === $key ? 'isActive' : '' ?>">
                    <div class="themeCardTop">
                        <h2><?= h($theme_item['label']) ?></h2>
                        <?php if ($current_theme === $key): ?>
                            <span class="activePill">Active</span>
                        <?php endif; ?>
                    </div>

                    <div class="themeSwitch <?= h($theme_item['switch']) ?>"></div>

                    <p><?= h($theme_item['description']) ?></p>

                    <input type="hidden" name="theme" value="<?= h($key) ?>">

                    <button type="submit" class="btnPrimary" <?= $current_theme === $key ? 'disabled' : '' ?>>
                        <?= $current_theme === $key ? 'Active Now' : 'Activate Theme' ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

</main>

</body>
</html>