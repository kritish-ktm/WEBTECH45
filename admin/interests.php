<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$db=$getDB=getDB(); $action=$_GET['action']??'list'; $id=isset($_GET['id'])?(int)$_GET['id']:0;

if($action==='delete'&&$id&&$_SERVER['REQUEST_METHOD']==='POST'){
    $db->prepare("DELETE FROM InterestedStudents WHERE InterestID=?")->execute([$id]);
    flashSet('success','Registration removed.'); redirect(BASE_URL.'/admin/interests.php');
}
if($action==='export'){
    $progID=isset($_GET['programme_id'])?(int)$_GET['programme_id']:0;
    $params=[]; $sql="SELECT i.StudentName,i.Email,p.ProgrammeName,i.RegisteredAt FROM InterestedStudents i JOIN Programmes p ON i.ProgrammeID=p.ProgrammeID";
    if($progID>0){$sql.=" WHERE i.ProgrammeID=?";$params[]=$progID;}
    $sql.=" ORDER BY p.ProgrammeName,i.RegisteredAt DESC";
    $stmt=$db->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="mailing-list-'.date('Y-m-d').'.csv"');
    $out=fopen('php://output','w');
    fputcsv($out,['Student Name','Email','Programme','Registered At']);
    foreach($rows as $row) fputcsv($out,[$row['StudentName'],$row['Email'],$row['ProgrammeName'],$row['RegisteredAt']]);
    fclose($out); exit;
}

$filterProg=isset($_GET['programme_id'])?(int)$_GET['programme_id']:0;
$params=[]; $sql="SELECT i.*,p.ProgrammeName FROM InterestedStudents i JOIN Programmes p ON i.ProgrammeID=p.ProgrammeID";
if($filterProg>0){$sql.=" WHERE i.ProgrammeID=?";$params[]=$filterProg;}
$sql.=" ORDER BY i.RegisteredAt DESC";
$stmt=$db->prepare($sql); $stmt->execute($params); $interests=$stmt->fetchAll();
$programmes=$db->query("SELECT ProgrammeID,ProgrammeName FROM Programmes ORDER BY ProgrammeName")->fetchAll();
$counts=$db->query("SELECT p.ProgrammeID,p.ProgrammeName,COUNT(i.InterestID) AS cnt FROM Programmes p LEFT JOIN InterestedStudents i ON i.ProgrammeID=p.ProgrammeID GROUP BY p.ProgrammeID HAVING cnt>0 ORDER BY cnt DESC")->fetchAll();

$pageTitle='Mailing Lists'; include __DIR__.'/includes/admin_header.php'; ?>

<div class="page-header">
    <div class="page-header-left"><div class="ku-divider-sm"></div><h1>Mailing Lists</h1><p>View and export student interest registrations.</p></div>
    <a href="/student_course_hub/admin/interests.php?action=export<?= $filterProg?'&programme_id='.$filterProg:'' ?>" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<!-- Summary stats -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    <?php foreach(array_slice($counts,0,4) as $c):?>
    <div class="stat-card red" style="cursor:pointer" onclick="location.href='/student_course_hub/admin/interests.php?programme_id=<?= $c['ProgrammeID'] ?>'">
        <div class="stat-card-icon"><i class="bi bi-envelope"></i></div>
        <div class="stat-card-value"><?= $c['cnt'] ?></div>
        <div class="stat-card-label" style="font-size:0.68rem;line-height:1.3"><?= h($c['ProgrammeName']) ?></div>
    </div>
    <?php endforeach;?>
</div>

<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title">Registrations (<?= count($interests) ?>)</span>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <form method="GET" action="/student_course_hub/admin/interests.php" style="display:flex;gap:8px;align-items:center">
                <label style="font-size:0.78rem;color:var(--grey-600)">Filter:</label>
                <select name="programme_id" class="table-search" style="width:auto" onchange="this.form.submit()">
                    <option value="0">All Programmes</option>
                    <?php foreach($programmes as $prog):?>
                    <option value="<?= $prog['ProgrammeID'] ?>" <?= $filterProg==(int)$prog['ProgrammeID']?'selected':''?>><?= h($prog['ProgrammeName']) ?></option>
                    <?php endforeach;?>
                </select>
            </form>
            <input type="search" class="table-search" placeholder="Search…" data-table-search="interestsTable">
        </div>
    </div>
    <table id="interestsTable">
        <thead><tr><th>#</th><th>Student</th><th>Email</th><th>Programme</th><th>Registered</th><th>Action</th></tr></thead>
        <tbody>
        <?php if(empty($interests)):?><tr class="empty-row"><td colspan="6">No registrations found.</td></tr>
        <?php else: foreach($interests as $row):?>
        <tr>
            <td class="text-muted text-small"><?= $row['InterestID'] ?></td>
            <td><strong><?= h($row['StudentName']) ?></strong></td>
            <td class="text-small"><?= h($row['Email']) ?></td>
            <td class="text-small"><?= h($row['ProgrammeName']) ?></td>
            <td class="text-muted text-small"><?= date('d M Y, H:i',strtotime($row['RegisteredAt'])) ?></td>
            <td>
                <div class="table-actions">
                    <form method="POST" action="/student_course_hub/admin/interests.php?action=delete&id=<?= $row['InterestID'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Remove registration for <?= h($row['StudentName']) ?>?"><i class="bi bi-trash"></i> Remove</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; endif;?>
        </tbody>
    </table>
</div>

<?php include __DIR__.'/includes/admin_footer.php'; ?>