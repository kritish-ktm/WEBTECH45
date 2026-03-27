<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (isAdminLoggedIn()) redirect(BASE_URL . '/admin/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!empty($username) && !empty($password)) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM Admins WHERE Username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['PasswordHash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['AdminID'];
            $_SESSION['admin_name'] = $admin['Username'];
            $_SESSION['admin_role'] = $admin['Role'];
            redirect(BASE_URL . '/admin/dashboard.php');
        } else {
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/student_course_hub/assets/css/admin.css">
</head>
<body>
<div class="login-page">
    <!-- Left panel -->
    <div class="login-left">
        <div class="login-left-content">
            <div class="login-left-emblem">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1>UniHub<br>Administration</h1>
            <p>Manage degree programmes, modules, academic staff and student mailing lists from one place.</p>
        </div>
    </div>
    <!-- Right panel -->
    <div class="login-right">
        <div class="login-card">
            <div class="login-divider"></div>
            <h2>Sign In</h2>
            <p class="login-sub">Enter your credentials to access the admin panel.</p>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle"></i> <?= h($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/student_course_hub/admin/login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           value="<?= isset($_POST['username']) ? h($_POST['username']) : '' ?>"
                           autocomplete="username" autofocus required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:11px; margin-top:4px">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>

            <p style="margin-top:20px; font-size:0.75rem; color:var(--grey-400); text-align:center">
                Default: <code>admin</code> / <code>admin123</code>
            </p>
        </div>
    </div>
</div>
</body>
</html>