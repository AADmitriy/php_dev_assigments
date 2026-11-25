<?php
include '../includes/session.php';
require '../config/db.php';
include '../includes/auth.php';

requireLogin();

$user = currentUser();

// Safety net: session says logged in, but the user row could not be loaded
if (!$user) {
    http_response_code(403);
    exit('403 Forbidden: please log in.');
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/registerFormStyles.css">
    <link rel="stylesheet" href="css/logoutFormStyles.css">
    <link rel="stylesheet" href="css/userPageStyles.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    ?>
    <div class="register-form-wrapper">

    <div class="register-form profile-card">
    <div class="register-form__header">
        <p class="register-form__title">My profile</p>
    </div>

    <div class="register-form__body profile-card__body">
        <!-- Default avatar -->
        <svg class="profile-card__avatar" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="Default avatar">
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="10" r="3.2" />
            <path d="M5.8 18.6a7 7 0 0 1 12.4 0" />
        </svg>

        <p class="profile-card__name"><?= e($user['username']) ?></p>
        <span class="profile-card__role"><?= e(ucfirst($user['role'])) ?></span>

        <dl class="profile-card__info">
            <div class="profile-card__row">
                <dt>User ID</dt>
                <dd>#<?= e($user['id']) ?></dd>
            </div>
            <div class="profile-card__row">
                <dt>Username</dt>
                <dd><?= e($user['username']) ?></dd>
            </div>
            <div class="profile-card__row">
                <dt>Email</dt>
                <dd><?= e($user['email']) ?></dd>
            </div>
            <div class="profile-card__row">
                <dt>Role</dt>
                <dd><?= e(ucfirst($user['role'])) ?></dd>
            </div>
        </dl>
    </div>

    <div class="register-form__footer profile-card__footer">
        <a href="/" class="logout-form__cancel">Home</a>
        <a href="logout.php" class="logout-form__cancel profile-card__logout">Log out</a>
    </div>
    </div>

    </div>
</body>
</html>