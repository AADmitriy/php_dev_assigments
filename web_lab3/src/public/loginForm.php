<?php
include '../includes/session.php';
require '../config/db.php';
include '../includes/auth.php';

$error = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (loginUser($login, $password)) {
        header('Location: /');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="css/cartStyles.css">
    <link rel="stylesheet" href="css/navbarStyles.css">
    <link rel="stylesheet" href="css/registerFormStyles.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include '../includes/navbar.php';
    ?>
    <div class="register-form-wrapper">

    <form method="POST" class="register-form">
    <div class="register-form__header">
        <p class="register-form__title">Log in</p>
    </div>

    <div class="register-form__body">
        <div class="register-form__field">
        <label for="login" class="register-form__label">Login</label>
        <input
            type="text" id="login" name="login" class="register-form__input" placeholder="Enter username or email"
            maxlength="250" value="<?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>"
            required
        />
        </div>

        <div class="register-form__field">
        <label for="password" class="register-form__label">Password</label>
        <input
            type="password" id="password" name="password" class="register-form__input" placeholder="Enter password"
            maxlength="100"
            required
        />
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="error-message" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="register-form__footer">
        <button type="submit" class="register-form__submit" name="login_user">
            Log in
        </button>
    </div>
    </form>

    </div>
</body>
</html>