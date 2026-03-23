<?php
require_once __DIR__ . '/../config.php';

require_admin();

$theme = normalize_theme((string) ($_GET['theme'] ?? 'light'));
set_active_theme($theme);

$redirect = trim((string) ($_GET['redirect'] ?? ''));

if (!is_safe_redirect($redirect)) {
    $redirect = project_base_path() . '/index.php';
}

redirect_to($redirect);