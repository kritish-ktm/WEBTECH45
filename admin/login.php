<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, // localhost
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (isAdminLoggedIn()) redirect(BASE_URL . '/admin/dashboard.php');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    if ($_SESSION['login_attempts'] >= 5 && time() - $_SESSION['last_attempt'] < 300) {
        die('Too many attempts. Try again later.');
    }

    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM Admins WHERE Username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        $hash = $admin['PasswordHash'] ?? password_hash('dummy', PASSWORD_DEFAULT);

        if ($admin && password_verify($password, $hash)) {
            session_regenerate_id(true);

            $_SESSION['admin_id']   = $admin['AdminID'];
            $_SESSION['admin_name'] = $admin['Username'];
            $_SESSION['admin_role'] = $admin['Role'];

            $_SESSION['login_attempts'] = 0;

            redirect(BASE_URL . '/admin/dashboard.php');
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — UniHub</title>
    <link rel="stylesheet" href="/student_course_hub/assets/css/admin.css">
</head>
<body>
<div class="login-page">
    <div class="login-right">
        <div class="login-card">
            <h2>Sign In</h2>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <?= h($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/student_course_hub/admin/login.php">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username"
                           value="<?= isset($_POST['username']) ? h($_POST['username']) : '' ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Sign In</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>