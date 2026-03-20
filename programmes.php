<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$db          = getDB();
$levelFilter = isset($_GET['level']) ? (int)$_GET['level'] : 0;
$search      = isset($_GET['q'])     ? sanitize($_GET['q']) : '';

$params = [];
$sql = "
    SELECT p.*, l.LevelName, s.Name AS LeaderName
    FROM Programmes p
    JOIN Levels l ON p.LevelID = l.LevelID
    LEFT JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
    WHERE p.Published = 1
";
if ($levelFilter > 0) { $sql .= " AND p.LevelID = ?"; $params[] = $levelFilter; }
if ($search !== '') {
    $sql .= " AND (p.ProgrammeName LIKE ? OR p.Description LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}
$sql .= " ORDER BY l.LevelID, p.ProgrammeName";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$programmes = $stmt->fetchAll();

$grouped = [];
foreach ($programmes as $prog) { $grouped[$prog['LevelName']][] = $prog; }

$levels    = $db->query("SELECT * FROM Levels")->fetchAll();
$pageTitle = 'All Programmes';
include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="/student_course_hub/">Home</a>
            <span aria-hidden="true">›</span>
            <span aria-current="page">Programmes</span>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="reveal" style="margin-bottom:32px">
            <div class="ku-divider"></div>
            <h1 class="section-heading">Our Programmes</h1>
            <p class="section-sub">Discover undergraduate and postgraduate degrees across Computing, AI, Cybersecurity, and more.</p>
        </div>

        <!-- Filter bar -->
        <form method="GET" action="/student_course_hub/programmes.php" role="search">
            <div class="filter-bar">
                <a href="/student_course_hub/programmes.php"
                   class="filter-btn <?= $levelFilter === 0 && $search === '' ? 'active' : '' ?>">
                    <i class="bi bi-grid"></i> All
                </a>
                <?php foreach ($levels as $level): ?>
                <a href="/student_course_hub/programmes.php?level=<?= $level['LevelID'] ?>"
                   class="filter-btn <?= $levelFilter === (int)$level['LevelID'] ? 'active' : '' ?>">
                    <?= h($level['LevelName']) ?>
                </a>
                <?php endforeach; ?>
                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" id="programmeSearch" name="q"
                           placeholder="Search programmes…"
                           value="<?= h($search) ?>"
                           aria-label="Search programmes">
                </div>
            </div>
        </form>

        <?php if (empty($programmes)): ?>
        <div class="empty-state">
            <i class="bi bi-search"></i>
            <h3>No programmes found</h3>
            <p>Try adjusting your search or filter.</p>
            <a href="/student_course_hub/programmes.php" class="btn btn-primary" style="margin-top:20px">Clear filters</a>
        </div>
        <?php else: ?>
            <?php foreach ($grouped as $levelName => $progs): ?>
            <div style="margin-bottom:56px">
                <h2 style="font-family:var(--font-serif); font-size:1.3rem; font-weight:700;
                    margin-bottom:24px; padding-bottom:12px; border-bottom:2px solid var(--grey-200);
                    display:flex; align-items:center; justify-content:space-between">
                    <?= h($levelName) ?> Programmes
                    <span style="font-size:0.82rem; font-weight:400; color:var(--grey-400); font-family:var(--font-sans)"><?= count($progs) ?> programmes</span>
                </h2>
                <div class="programme-grid">
                    <?php foreach ($progs as $i => $prog): ?>
                    <div class="card-wrapper">
                        <a href="/student_course_hub/programme.php?id=<?= (int)$prog['ProgrammeID'] ?>"
                           class="programme-card reveal reveal-delay-<?= min($i,4) ?>">
                            <div class="card-image-wrap">
                                <?php if (!empty($prog['Image'])): ?>
                                    <img src="<?= h($prog['Image']) ?>" alt="<?= h($prog['ProgrammeName']) ?>">
                                <?php else: ?>
                                    <div class="card-image-placeholder"><i class="bi bi-mortarboard"></i></div>
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
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>