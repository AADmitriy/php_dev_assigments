<?php
// auth.php - simple user helpers (requires $pdo and sessions to be set before including)

/**
 * Register a new user.
 * Returns true on success, or an error message string on failure.
 */
function registerUser(string $username, string $email, string $password)
{
    global $pdo;

    $username = trim($username);
    $email    = trim($email);

    if ($username === '' || $email === '' || $password === '') {
        return 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return 'Username or email is already taken.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
    $stmt->execute([$username, $email, $hash]);

    return true;
}

/**
 * Log a user in by username or email.
 * Returns true on success, false on wrong credentials.
 */
function loginUser(string $login, string $password): bool
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ? OR email = ?');
    $stmt->execute([trim($login), trim($login)]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true); // prevents session fixation
    $_SESSION['user_id'] = (int)$user['id'];
    return true;
}

/**
 * Log the current user out and destroy the session.
 */
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
}

/**
 * Is someone logged in?
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Get the logged-in user as an array (id, username, email, role), or null.
 * Loaded from the database once per request.
 */
function currentUser(): ?array
{
    global $pdo;
    static $user = null;

    if (!isLoggedIn()) {
        return null;
    }

    if ($user === null) {
        $stmt = $pdo->prepare('SELECT id, username, email, role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($user === null) { // user was deleted from the database
            logoutUser();
        }
    }

    return $user;
}

/**
 * Is the logged-in user an admin?
 */
function isAdmin(): bool
{
    $user = currentUser();
    return $user !== null && $user['role'] === 'admin';
}


/**
 * Protect user only content
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        http_response_code(403);
        require __DIR__ . '/../public/403.php';
        exit;
    }
}

/**
 * Protect admin only content
 */
function requireAdmin(): void
{
    if (!isAdmin()) {
        http_response_code(403);
        require __DIR__ . '/../public/403.php';
        exit;
    }
}