<?php
/* Shameer Sheikh worked on the frontend admin interface parts in this file. */
require_once __DIR__ . '/../config.php';

require_admin();

/* Save the selected admin theme choice */
$theme = normalize_theme((string) ($_GET['theme'] ?? 'light'));
set_active_theme($theme);

$redirect = trim((string) ($_GET['redirect'] ?? ''));

/* Only allow safe internal redirects after the theme is changed */
if (!is_safe_redirect($redirect)) {
    $redirect = project_base_path() . '/index.php';
}

redirect_to($redirect);