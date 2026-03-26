<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? 'Student Course Hub') ?> — UniHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/student_course_hub/assets/css/public.css">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

<div class="top-bar">
    <div class="container">
        <a href="/student_course_hub/admin/login.php"><i class="bi bi-shield-lock"></i> Admin Login</a>
        <a href="/student_course_hub/staff/login.php"><i class="bi bi-shield-lock"></i> Staff Login</a>
        <span>|</span>
        <span><i class="bi bi-geo-alt"></i> London, United Kingdom</span>
    </div>
</div>

<header class="site-header" role="banner">
    <div class="container header-inner">
        <a href="/student_course_hub/" class="site-logo" aria-label="UniHub Home">
            <div class="logo-emblem">
                <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>
            </div>
            <div class="logo-text-wrap">
                <span class="logo-name">UniHub</span>
                <span class="logo-sub">University Programmes</span>
            </div>
        </a>

        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <nav id="mainNav" class="main-nav" role="navigation" aria-label="Main navigation">
            <ul>
                <li><a href="/student_course_hub/"><i class="bi bi-house-door"></i> Home</a></li>
                <li><a href="/student_course_hub/programmes.php"><i class="bi bi-grid-3x3-gap"></i> Programmes</a></li>
                <li><a href="/student_course_hub/programmes.php?level=1"><i class="bi bi-book"></i> Undergraduate</a></li>
                <li><a href="/student_course_hub/programmes.php?level=2"><i class="bi bi-journal-bookmark"></i> Postgraduate</a></li>
            </ul>
        </nav>
    </div>
</header>

<main id="main-content">