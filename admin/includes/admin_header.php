<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? 'Admin') ?> — UniHub Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/student_course_hub/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">

<!-- Sidebar -->
<aside class="sidebar" role="navigation" aria-label="Admin navigation">
    <a href="/student_course_hub/admin/dashboard.php" class="sidebar-logo">
        <div class="sidebar-logo-emblem">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div class="sidebar-logo-text">
            <span class="sidebar-logo-name">UniHub</span>
            <span class="sidebar-logo-sub">Admin Panel</span>
        </div>
    </a>

    <div class="sidebar-section">
        <p class="sidebar-section-label">Overview</p>
        <ul class="sidebar-nav">
            <li>
                <a href="/student_course_hub/admin/dashboard.php"
                   class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-section">
        <p class="sidebar-section-label">Content</p>
        <ul class="sidebar-nav">
            <li>
                <a href="/student_course_hub/admin/programmes.php"
                   class="<?= strpos(basename($_SERVER['PHP_SELF']), 'programme') !== false ? 'active' : '' ?>">
                    <i class="bi bi-mortarboard nav-icon"></i> Programmes
                </a>
            </li>
            <li>
                <a href="/student_course_hub/admin/modules.php"
                   class="<?= strpos(basename($_SERVER['PHP_SELF']), 'module') !== false ? 'active' : '' ?>">
                    <i class="bi bi-journal-text nav-icon"></i> Modules
                </a>
            </li>
            <li>
                <a href="/student_course_hub/admin/staff.php"
                   class="<?= basename($_SERVER['PHP_SELF']) === 'staff.php' ? 'active' : '' ?>">
                    <i class="bi bi-people nav-icon"></i> Staff
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-section">
        <p class="sidebar-section-label">Students</p>
        <ul class="sidebar-nav">
            <li>
                <a href="/student_course_hub/admin/interests.php"
                   class="<?= basename($_SERVER['PHP_SELF']) === 'interests.php' ? 'active' : '' ?>">
                    <i class="bi bi-envelope-paper nav-icon"></i> Mailing Lists
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <a href="/student_course_hub/" target="_blank">
            <i class="bi bi-globe nav-icon"></i> View Website
        </a>
        <a href="/student_course_hub/admin/logout.php" class="danger">
            <i class="bi bi-box-arrow-right nav-icon"></i> Sign Out
        </a>
    </div>
</aside>

<!-- Main -->
<div class="admin-main">
    <header class="admin-topbar">
        <div class="topbar-left">
            <span class="topbar-title">UniHub</span>
            <span class="topbar-divider">/</span>
            <span class="topbar-page"><?= h($pageTitle ?? 'Dashboard') ?></span>
        </div>
        <div class="topbar-right">
            <div class="topbar-user">
                <i class="bi bi-person-circle" style="font-size:1rem"></i>
                <span><?= h($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <div class="topbar-avatar"><?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?></div>
            </div>
        </div>
    </header>
    <div class="admin-content">

<?php $flash = flashGet(); if ($flash): ?>
<div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" role="alert">
    <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
    <?= h($flash['message']) ?>
</div>
<?php endif; ?>