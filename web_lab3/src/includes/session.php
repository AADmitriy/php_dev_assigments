<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        // 'secure' => true, // enable when the site runs on HTTPS
    ]);
    if (!is_array($_SESSION['cart'] ?? null)) {
        $_SESSION['cart'] = [];
    }

    session_start();
}