<?php
require_once __DIR__ . '/includes/db.php';

$db       = getDB();
$username = 'admin';
$password = 'admin123';
$hash     = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$existing = $db->prepare("SELECT AdminID FROM Admins WHERE Username = ?");
$existing->execute([$username]);

if ($existing->fetch()) {
    $db->prepare("UPDATE Admins SET PasswordHash = ? WHERE Username = ?")->execute([$hash, $username]);
    echo "<p style='font-family:sans-serif; color:green'>✓ Admin password reset to <strong>admin123</strong>. Please delete this file!</p>";
} else {
    $db->prepare("INSERT INTO Admins (Username, PasswordHash, Role) VALUES (?, ?, 'super_admin')")->execute([$username, $hash]);
    echo "<p style='font-family:sans-serif; color:green'>✓ Admin account created. Username: <strong>admin</strong> / Password: <strong>admin123</strong>.</p>";
}

echo "<p style='font-family:sans-serif'><a href='" . BASE_URL . "/admin/login.php'>→ Go to Admin Login</a></p>";
echo "<p style='font-family:sans-serif; color:red; font-weight:bold'>⚠ DELETE this file (setup.php) after use!</p>";