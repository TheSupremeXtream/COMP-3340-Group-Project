<?php
require_once __DIR__ . '/../backend/config.php';

if (is_logged_in()) {
    logout_user();
}

redirect_to(BASE_URL . 'index.php');