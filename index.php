Arun
arun0422
Online



Everyone welcome 
ScriptSeeker_X
! — Yesterday at 11.26

Wave to say hi!
ScriptSeeker_X — Yesterday at 11.26
Arun — Yesterday at 11.26
yoyo
ScriptSeeker_X — Yesterday at 11.27
class mai ho sathi?
A wild 
CARINATUS
 appeared. — Yesterday at 11.27

Wave to say hi!
ScriptSeeker_X — Yesterday at 11.27
Arun — Yesterday at 11.27
aba kam suru garam hai
ScriptSeeker_X — Yesterday at 11.28
garam garam suru garam
1st ma arun sathi ko pathauxu haii
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$db = getDB();

index.php
8 KB
<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$db = getDB();

programme.php
12 KB
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$db          = getDB();
$levelFilter = isset($_GET['level']) ? (int)$_GET['level'] : 0;

programmes.php
6 KB
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_course_hub');
define('BASE_URL', '/student_course_hub');

db.php
1 KB
</main>
<footer class="site-footer" role="contentinfo">
    <div class="container footer-inner">
        <div class="footer-brand">
            <div class="footer-logo">
                <i class="bi bi-mortarboard-fill logo-icon" aria-hidden="true"></i>

footer.php
2 KB
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? 'Student Course Hub') ?> — UniHub</title>

header.php
3 KB
<?php
function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): void {

helpers.php
1 KB
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@400;600;700&display=swap');

:root {
    --ku-red:       #901A1E;
    --ku-red-dark:  #6e1316;
    --ku-red-light: #f5e8e8;

public.css
27 KB
ScriptSeeker_X — Yesterday at 12.40
Image
Image
Image
Image
Image
Image
Image
Image
Image
Image
Image
paile git pull origin main gara haii kta ho asti banako file ma gayera
ScriptSeeker_X — Yesterday at 13.46
https://claude.ai/share/903003cc-d006-49a1-8122-186a77a9e148
Image
Beluka Online aau hai kta ho 11:30 ma
Arun — Yesterday at 20.15
okok
CARINATUS — Yesterday at 20.16
Okay
11 baje
Sab jana online
ScriptSeeker_X — Yesterday at 23.34
Lala
Ma Chai aaba Monday Matra free hunxu
Code Pathaidinxu Garao aafai
CARINATUS — Yesterday at 23.57
Ko ho yo
Arun — 08.38
maile yo banyera commit haru chai gare hai
Image
﻿
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$db = getDB();

$featured = $db->query("
    SELECT p.ProgrammeID, p.ProgrammeName, p.Description, p.Image,
           p.LevelID, l.LevelName, s.Name AS LeaderName
    FROM Programmes p
    JOIN Levels l ON p.LevelID = l.LevelID
    LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
    WHERE p.Published = 1
    ORDER BY RAND() LIMIT 3
")->fetchAll();

$stats = $db->query("
    SELECT
        (SELECT COUNT(*) FROM Programmes WHERE Published = 1) AS total_programmes,
        (SELECT COUNT(*) FROM Modules)                        AS total_modules,
        (SELECT COUNT(*) FROM Staff)                          AS total_staff,
        (SELECT COUNT(*) FROM InterestedStudents)             AS total_interest
")->fetch();

$pageTitle = 'Welcome';
include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg" style="background-image:url('/student_course_hub/assets/bc.jpg')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <p class="hero-kicker animate-fade-down">University Programmes</p>
            <h1 class="animate-fade-up">Discover Your<br>Academic Future</h1>
            <p class="hero-lead animate-fade-up animate-delay-1">
                Explore undergraduate and postgraduate degrees in computing, artificial intelligence,
                cybersecurity and more — taught by world-class faculty.
            </p>
            <div class="hero-actions animate-fade-up animate-delay-2">
                <a href="/student_course_hub/programmes.php" class="btn btn-primary">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Browse Programmes
                </a>
                <a href="/student_course_hub/programmes.php?level=2" class="btn btn-outline-white">
                    <i class="bi bi-journal-bookmark-fill"></i> Postgraduate
                </a>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="stats-bar">
    <div class="container">
        <div class="stats-inner">
            <div class="stat-item reveal">
                <span class="stat-number" data-count="<?= (int)$stats['total_programmes'] ?>">0</span>
                <span class="stat-label"><i class="bi bi-mortarboard"></i> Programmes</span>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <span class="stat-number" data-count="<?= (int)$stats['total_modules'] ?>">0</span>
                <span class="stat-label"><i class="bi bi-journal-text"></i> Modules</span>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <span class="stat-number" data-count="<?= (int)$stats['total_staff'] ?>">0</span>
                <span class="stat-label"><i class="bi bi-people"></i> Academic Staff</span>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <span class="stat-number" data-count="<?= (int)$stats['total_interest'] ?>">0</span>
                <span class="stat-label"><i class="bi bi-envelope-check"></i> Registered</span>
            </div>
        </div>
    </div>
</div>

<!-- FEATURED PROGRAMMES -->
<section class="section">
    <div class="container">
        <div class="section-header-row reveal">
            <div>
                <div class="ku-divider"></div>
                <h2 class="section-heading">Featured Programmes</h2>
                <p class="section-sub">A selection of our most popular degrees across computing and technology.</p>
            </div>
            <a href="/student_course_hub/programmes.php" class="btn btn-outline-red">
                All Programmes <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="programme-grid">
            <?php foreach ($featured as $i => $prog): ?>
            <a href="/student_course_hub/programme.php?id=<?= (int)$prog['ProgrammeID'] ?>"
               class="programme-card reveal reveal-delay-<?= $i ?>">
                <div class="card-image-wrap">
                    <?php if (!empty($prog['Image'])): ?>
                        <img src="<?= h($prog['Image']) ?>" alt="<?= h($prog['ProgrammeName']) ?>">
                    <?php else: ?>
                        <div class="card-image-placeholder">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <span class="card-level <?= $prog['LevelID'] == 1 ? 'level-ug' : 'level-pg' ?>">
                        <?= h($prog['LevelName']) ?>
                    </span>
                    <h3><?= h($prog['ProgrammeName']) ?></h3>
                    <p><?= h(substr($prog['Description'] ?? '', 0, 110)) ?>…</p>
                    <div class="card-meta">
                        <span><i class="bi bi-person"></i> <?= h($prog['LeaderName'] ?? 'TBC') ?></span>
                        <span class="card-arrow"><i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- STUDY LEVEL SPLIT -->
<section class="section section-grey">
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center">
            <div class="reveal">
                <div class="ku-divider"></div>
                <h2 class="section-heading">Undergraduate &amp;<br>Postgraduate Study</h2>
                <p style="color:var(--grey-600); margin-bottom:28px; font-weight:300; line-height:1.7">
                    Whether you are beginning your academic journey with a BSc or advancing your expertise
                    with an MSc, our programmes are designed to prepare you for a rapidly evolving digital world.
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap">
                    <a href="/student_course_hub/programmes.php?level=1" class="btn btn-primary">
                        <i class="bi bi-book"></i> Undergraduate
                    </a>
                    <a href="/student_course_hub/programmes.php?level=2" class="btn btn-ghost">
                        <i class="bi bi-journal-bookmark"></i> Postgraduate
                    </a>
                </div>
            </div>
            <div class="info-tiles">
                <div class="info-tile reveal reveal-delay-1">
                    <div class="info-tile-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <strong>BSc Degrees</strong>
                    <p>3-year undergraduate programmes</p>
                </div>
                <div class="info-tile reveal reveal-delay-2">
                    <div class="info-tile-icon"><i class="bi bi-award"></i></div>
                    <strong>MSc Degrees</strong>
                    <p>1-year specialist programmes</p>
                </div>
                <div class="info-tile reveal reveal-delay-3">
                    <div class="info-tile-icon"><i class="bi bi-cpu"></i></div>
                    <strong>Tech-Focused</strong>
                    <p>Computing &amp; AI disciplines</p>
                </div>
                <div class="info-tile reveal reveal-delay-4">
                    <div class="info-tile-icon"><i class="bi bi-envelope-paper"></i></div>
                    <strong>Stay Updated</strong>
                    <p>Register your interest today</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
