<?php
require_once __DIR__ . '/../config.php';

// if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: ../login.php?redirect=admin/theme-settings.php');
//     exit;
// }

$themes = [
    'light' => [
        'label' => 'Light Theme',
        'description' => 'Default bright layout with the original store look and red accents.',
        'swatch' => 'swatch-light',
    ],
    'dark' => [
        'label' => 'Dark Theme',
        'description' => 'Dark layout for a cooler look that still keeps the same site structure.',
        'swatch' => 'swatch-dark',
    ],
    'holiday' => [
        'label' => 'Holiday Theme',
        'description' => 'Festive green and red layout for seasonal promotions and special events.',
        'swatch' => 'swatch-holiday',
    ],
];

$message = '';
$message_class = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_theme = trim($_POST['theme'] ?? '');

    if (!isset($themes[$selected_theme])) {
        $message = 'Invalid theme selected.';
        $message_class = 'error';
    } elseif (set_active_theme($selected_theme)) {
        $message = $themes[$selected_theme]['label'] . ' is now active.';
    } else {
        $message = 'Unable to update the active theme.';
        $message_class = 'error';
    }
}

$current_theme = get_active_theme();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Template Manager — The Computer Store</title>
    <link rel="stylesheet" href="../../styles/<?= h($current_theme) ?>.css">
    <link rel="stylesheet" href="../../styles/admin-theme.css">
</head>
<body class="theme-<?= h($current_theme) ?>">

<div class="container">
    <div class="navOuter">
        <div class="navInner">
            <a href="../../index.php" class="banner">
                <img src="../../images/logo.png" alt="The Computer Store" height="60">The Computer Store
            </a>
            <ul class="navList">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../monitor.php">Monitor</a></li>
                <li><a href="theme-settings.php">Templates</a></li>
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

        <p class="currentTheme">Current template: <?= h($themes[$current_theme]['label']) ?></p>
        <p class="templateNote">Your existing <code>active_theme</code> database setting is now being used for the site theme.</p>
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

                    <div class="themeSwatch <?= h($theme_item['swatch']) ?>"></div>

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