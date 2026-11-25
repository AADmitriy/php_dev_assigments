<?php
include '../includes/session.php';
require '../config/db.php';
include '../includes/auth.php';

$error = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    if ($password !== $repeat_password) {
        $error = 'Passwords do not match';
    }
    else {
        $result = registerUser($username, $email, $password);
    
        if ($result === true) {
            header('Location: loginForm.php');
            exit;
        }
    
        $error = $result; // error message string
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Form</title>
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
        <p class="register-form__title">Create an account</p>
    </div>


    <div class="register-form__body">
        <div class="register-form__field">
        <label for="username" class="register-form__label">Username</label>
        <input
            type="text" id="username" name="username" class="register-form__input" placeholder="Your username"
            value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
            maxlength="50"
            required
        />
        </div>

        <div class="register-form__field">
        <label for="email" class="register-form__label">Email</label>
        <input
            type="email" id="email" name="email" class="register-form__input" placeholder="name@example.com"
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
            maxlength="250"
            required
        />
        </div>

        <div class="register-form__field">
        <label for="password" class="register-form__label">Password</label>
        <input
            type="password" id="password" name="password" class="register-form__input" placeholder="Enter password"
            minlength="8" maxlength="100"
            required
        />
        </div>

        <div class="register-form__field">
        <label for="repeatPassword" class="register-form__label">Repeat password</label>
        <input
            type="password" id="repeatPassword" name="repeat_password" class="register-form__input" placeholder="Repeat password"
            minlength="8" maxlength="100"
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
        <button type="submit" class="register-form__submit" name="register">
            Register
        </button>
    </div>
    </form>

    </div>
</body>
</html>