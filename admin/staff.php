<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$db=$getDB=getDB(); $action=$_GET['action']??'list'; $id=isset($_GET['id'])?(int)$_GET['id']:0;

if($action==='delete'&&$id&&$_SERVER['REQUEST_METHOD']==='POST'){
    $db->prepare("DELETE FROM Staff WHERE StaffID=?")->execute([$id]);
    flashSet('success','Staff member removed.'); redirect(BASE_URL.'/admin/staff.php');
}
$errors=[]; $formData=['Name'=>'','Title'=>'','Bio'=>'','Department'=>''];
if($action==='edit'&&$id){
    $stmt=$db->prepare("SELECT * FROM Staff WHERE StaffID=?"); $stmt->execute([$id]);
    $editStaff=$stmt->fetch();
    if(!$editStaff){flashSet('error','Not found.');redirect(BASE_URL.'/admin/staff.php');}
    $formData=array_merge($formData,$editStaff);
}
if($_SERVER['REQUEST_METHOD']==='POST'&&in_array($action,['create','edit'])){
    $formData=['Name'=>sanitize($_POST['Name']??''),'Title'=>sanitize($_POST['Title']??''),'Bio'=>sanitize($_POST['Bio']??''),'Department'=>sanitize($_POST['Department']??'')];
    if(empty($formData['Name']))$errors[]='Name is required.';
    if(empty($errors)){
        if($action==='create'){$db->prepare("INSERT INTO Staff (Name,Title,Bio,Department) VALUES (?,?,?,?)")->execute(array_values($formData));flashSet('success','Staff member created.');}
        else{$db->prepare("UPDATE Staff SET Name=?,Title=?,Bio=?,Department=? WHERE StaffID=?")->execute([...array_values($formData),$id]);flashSet('success','Staff updated.');}
        redirect(BASE_URL.'/admin/staff.php');
    }
}

if(in_array($action,['create','edit'])){
    $pageTitle=$action==='create'?'Add Staff':'Edit Staff';
    include __DIR__.'/includes/admin_header.php'; ?>
    <div class="page-header">
        <div class="page-header-left"><div class="ku-divider-sm"></div><h1><?= $pageTitle ?></h1></div>
        <a href="/student_course_hub/admin/staff.php" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Cancel</a>
    </div>
    <?php if(!empty($errors)):?><div class="alert alert-error"><?php foreach($errors as $e) echo h($e).'<br>';?></div><?php endif;?>
    <div class="form-card">
        <form method="POST" action="/student_course_hub/admin/staff.php?action=<?= $action ?><?= $action==='edit'?'&id='.$id:'' ?>">
            <div class="form-row">
                <div class="form-group"><label>Full Name *</label><input type="text" name="Name" value="<?= h($formData['Name']) ?>" required></div>
                <div class="form-group"><label>Job Title</label><input type="text" name="Title" value="<?= h($formData['Title']??'') ?>" placeholder="e.g. Senior Lecturer"></div>
            </div>
            <div class="form-group"><label>Department</label><input type="text" name="Department" value="<?= h($formData['Department']??'') ?>" placeholder="e.g. School of Computing"></div>
            <div class="form-group"><label>Biography</label><textarea name="Bio" rows="4"><?= h($formData['Bio']??'') ?></textarea></div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> <?= $action==='create'?'Add Staff Member':'Save Changes' ?></button>
                <a href="/student_course_hub/admin/staff.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php include __DIR__.'/includes/admin_footer.php'; exit;
}

$staff=$db->query("SELECT s.*,COUNT(DISTINCT m.ModuleID) AS ModuleCount,COUNT(DISTINCT p.ProgrammeID) AS ProgrammeCount FROM Staff s LEFT JOIN Modules m ON m.ModuleLeaderID=s.StaffID LEFT JOIN Programmes p ON p.ProgrammeLeaderID=s.StaffID GROUP BY s.StaffID ORDER BY s.Name")->fetchAll();
$pageTitle='Staff'; include __DIR__.'/includes/admin_header.php'; ?>

<div class="page-header">
    <div class="page-header-left"><div class="ku-divider-sm"></div><h1>Staff</h1><p>Manage academic staff and their roles.</p></div>
    <a href="/student_course_hub/admin/staff.php?action=create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Staff</a>
</div>
<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title">All Staff (<?= count($staff) ?>)</span>
        <input type="search" class="table-search" placeholder="Search…" data-table-search="staffTable">
    </div>
    <table id="staffTable">
        <thead><tr><th>Name</th><th>Title / Department</th><th>Modules</th><th>Programmes</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if(empty($staff)):?><tr class="empty-row"><td colspan="5">No staff found.</td></tr>
        <?php else: foreach($staff as $s):?>
        <tr>
            <td><strong><?= h($s['Name']) ?></strong></td>
            <td class="text-small">
                <?= !empty($s['Title'])?h($s['Title']):'—' ?>
                <?php if(!empty($s['Department'])):?><br><span class="text-muted"><?= h($s['Department']) ?></span><?php endif;?>
            </td>
            <td><span class="badge badge-blue"><?= $s['ModuleCount'] ?></span></td>
            <td><span class="badge badge-orange"><?= $s['ProgrammeCount'] ?></span></td>
            <td>
                <div class="table-actions">
                    <a href="/student_course_hub/admin/staff.php?action=edit&id=<?= $s['StaffID'] ?>" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <form method="POST" action="/student_course_hub/admin/staff.php?action=delete&id=<?= $s['StaffID'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Remove '<?= h($s['Name']) ?>'?"><i class="bi bi-trash"></i> Remove</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; endif;?>
        </tbody>
    </table>
</div>
<?php include __DIR__.'/includes/admin_footer.php'; ?>