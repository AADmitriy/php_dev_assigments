<?php
include '../includes/session.php';
require '../config/db.php';
include '../includes/cartActions.php';
include '../includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout Form</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/registerFormStyles.css">
    <link rel="stylesheet" href="css/logoutFormStyles.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    ?>
    <div class="register-form-wrapper">

    <form method="POST" class="register-form logout-form">
    <div class="register-form__header">
        <p class="register-form__title">Log out</p>
    </div>

    <div class="register-form__body logout-form__body">
        <svg class="logout-form__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        <p class="logout-form__question">Are you sure you want to logout?</p>
    </div>

    <div class="register-form__footer logout-form__footer">
        <a href="javascript:history.back()" class="logout-form__cancel">Cancel</a>
        <button type="submit" class="register-form__submit logout-form__confirm" name="logout">
            Yes, logout
        </button>
    </div>
    </form>

    </div>

    <script src="js/bookCover.js"></script>
</body>
</html>