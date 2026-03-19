<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$db    = getDB();
$stats = $db->query("
    SELECT
        (SELECT COUNT(*) FROM Programmes)              AS total_programmes,
        (SELECT COUNT(*) FROM Programmes WHERE Published=1) AS published,
        (SELECT COUNT(*) FROM Modules)                 AS total_modules,
        (SELECT COUNT(*) FROM Staff)                   AS total_staff,
        (SELECT COUNT(*) FROM InterestedStudents)      AS total_interest,
        (SELECT COUNT(*) FROM InterestedStudents WHERE DATE(RegisteredAt)=CURDATE()) AS today_interest
")->fetch();

$recent = $db->query("
    SELECT i.StudentName, i.Email, i.RegisteredAt, p.ProgrammeName
    FROM InterestedStudents i
    JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
    ORDER BY i.RegisteredAt DESC LIMIT 8
")->fetchAll();

$topProgrammes = $db->query("
    SELECT p.ProgrammeName, COUNT(i.InterestID) AS cnt
    FROM InterestedStudents i
    JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
    GROUP BY p.ProgrammeID ORDER BY cnt DESC LIMIT 5
")->fetchAll();

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/admin_header.php';
?>

<div class="page-header">
    <div class="page-header-left">
        <div class="ku-divider-sm"></div>
        <h1>Dashboard</h1>
        <p>Welcome back, <?= h($_SESSION['admin_name']) ?>. Here is an overview of the system.</p>
    </div>
    <a href="/student_course_hub/admin/programmes.php?action=create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Programme
    </a>
</div>

<div class="stat-cards">
    <div class="stat-card red">
        <div class="stat-card-icon"><i class="bi bi-mortarboard"></i></div>
        <div class="stat-card-value"><?= $stats['total_programmes'] ?></div>
        <div class="stat-card-label">Programmes</div>
        <div class="stat-card-sub"><?= $stats['published'] ?> published</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-card-icon"><i class="bi bi-journal-text"></i></div>
        <div class="stat-card-value"><?= $stats['total_modules'] ?></div>
        <div class="stat-card-label">Modules</div>
        <div class="stat-card-sub">Across all programmes</div>
    </div>
    <div class="stat-card green">
        <div class="stat-card-icon"><i class="bi bi-people"></i></div>
        <div class="stat-card-value"><?= $stats['total_staff'] ?></div>
        <div class="stat-card-label">Staff Members</div>
        <div class="stat-card-sub">Academic staff</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-card-icon"><i class="bi bi-envelope-paper"></i></div>
        <div class="stat-card-value"><?= $stats['total_interest'] ?></div>
        <div class="stat-card-label">Registrations</div>
        <div class="stat-card-sub"><?= $stats['today_interest'] ?> today</div>
    </div>
</div>

<div class="two-col">
    <div class="table-wrap">
        <div class="table-toolbar">
            <span class="table-toolbar-title"><i class="bi bi-clock-history"></i> Recent Registrations</span>
            <a href="/student_course_hub/admin/interests.php" class="btn btn-outline btn-sm">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <table>
            <thead>
                <tr><th>Student</th><th>Programme</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php if (empty($recent)): ?>
                <tr class="empty-row"><td colspan="3">No registrations yet.</td></tr>
                <?php else: foreach ($recent as $r): ?>
                <tr>
                    <td>
                        <strong><?= h($r['StudentName']) ?></strong><br>
                        <span class="text-muted text-small"><?= h($r['Email']) ?></span>
                    </td>
                    <td class="text-small"><?= h($r['ProgrammeName']) ?></td>
                    <td class="text-muted text-small"><?= date('d M Y', strtotime($r['RegisteredAt'])) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-wrap">
        <div class="table-toolbar">
            <span class="table-toolbar-title"><i class="bi bi-bar-chart"></i> Top by Interest</span>
        </div>
        <table>
            <thead><tr><th>Programme</th><th>Count</th></tr></thead>
            <tbody>
                <?php if (empty($topProgrammes)): ?>
                <tr class="empty-row"><td colspan="2">No data yet.</td></tr>
                <?php else: foreach ($topProgrammes as $tp): ?>
                <tr>
                    <td class="text-small"><?= h($tp['ProgrammeName']) ?></td>
                    <td><span class="badge badge-red"><?= $tp['cnt'] ?></span></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>