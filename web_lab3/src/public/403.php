<?php
include '../includes/session.php';
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/registerFormStyles.css">
    <link rel="stylesheet" href="css/403Styles.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="register-form-wrapper">
        <div class="register-form error-403">
            <div class="register-form__header">
                <p class="register-form__title">Access denied</p>
            </div>

            <div class="register-form__body error-403__body">
                <h1 class="error-403__code">403</h1>
                <p class="error-403__status">Forbidden</p>
                <p class="error-403__message">We are sorry, but you do not have access to this page or resource</p>
            </div>

            <div class="register-form__footer">
                <a href="/" class="register-form__submit error-403__home">Go back home</a>
            </div>
        </div>
    </div>

    <script src="js/bookCover.js"></script>
</body>
</html>