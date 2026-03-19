<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$db=$getDB=getDB(); $action=$_GET['action']??'list'; $id=isset($_GET['id'])?(int)$_GET['id']:0;

if($action==='delete'&&$id&&$_SERVER['REQUEST_METHOD']==='POST'){
    $db->prepare("DELETE FROM Modules WHERE ModuleID=?")->execute([$id]);
    flashSet('success','Module deleted.'); redirect(BASE_URL.'/admin/modules.php');
}
$errors=[]; $formData=['ModuleName'=>'','ModuleLeaderID'=>'','Description'=>'','Image'=>''];
if($action==='edit'&&$id){
    $stmt=$db->prepare("SELECT * FROM Modules WHERE ModuleID=?"); $stmt->execute([$id]);
    $editModule=$stmt->fetch();
    if(!$editModule){flashSet('error','Not found.');redirect(BASE_URL.'/admin/modules.php');}
    $formData=$editModule;
}
if($_SERVER['REQUEST_METHOD']==='POST'&&in_array($action,['create','edit'])){
    $formData=['ModuleName'=>sanitize($_POST['ModuleName']??''),'ModuleLeaderID'=>(int)($_POST['ModuleLeaderID']??0),'Description'=>sanitize($_POST['Description']??''),'Image'=>sanitize($_POST['Image']??'')];
    if(empty($formData['ModuleName']))$errors[]='Module name is required.';
    if(empty($errors)){
        if($action==='create'){$db->prepare("INSERT INTO Modules (ModuleName,ModuleLeaderID,Description,Image) VALUES (?,?,?,?)")->execute(array_values($formData));flashSet('success','Module created.');}
        else{$db->prepare("UPDATE Modules SET ModuleName=?,ModuleLeaderID=?,Description=?,Image=? WHERE ModuleID=?")->execute([...array_values($formData),$id]);flashSet('success','Module updated.');}
        redirect(BASE_URL.'/admin/modules.php');
    }
}
$staff=$db->query("SELECT * FROM Staff ORDER BY Name")->fetchAll();

if(in_array($action,['create','edit'])){
    $pageTitle=$action==='create'?'Create Module':'Edit Module';
    include __DIR__.'/includes/admin_header.php'; ?>
    <div class="page-header">
        <div class="page-header-left"><div class="ku-divider-sm"></div><h1><?= $pageTitle ?></h1></div>
        <a href="/student_course_hub/admin/modules.php" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Cancel</a>
    </div>
    <?php if(!empty($errors)):?><div class="alert alert-error"><?php foreach($errors as $e) echo h($e).'<br>';?></div><?php endif;?>
    <div class="form-card">
        <form method="POST" action="/student_course_hub/admin/modules.php?action=<?= $action ?><?= $action==='edit'?'&id='.$id:'' ?>">
            <div class="form-group"><label>Module Name *</label><input type="text" name="ModuleName" value="<?= h($formData['ModuleName']) ?>" required></div>
            <div class="form-group"><label>Module Leader</label>
                <select name="ModuleLeaderID"><option value="">Select staff…</option>
                <?php foreach($staff as $s):?><option value="<?= $s['StaffID'] ?>" <?= $formData['ModuleLeaderID']==$s['StaffID']?'selected':''?>><?= h($s['Name'])?></option><?php endforeach;?>
                </select>
            </div>
            <div class="form-group"><label>Description</label><textarea name="Description" rows="4"><?= h($formData['Description']) ?></textarea></div>
            <div class="form-group"><label>Image URL</label><input type="text" name="Image" value="<?= h($formData['Image']) ?>" placeholder="https://…"></div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> <?= $action==='create'?'Create Module':'Save Changes' ?></button>
                <a href="/student_course_hub/admin/modules.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
    <?php include __DIR__.'/includes/admin_footer.php'; exit;
}

$modules=$db->query("SELECT m.*,s.Name AS LeaderName,COUNT(DISTINCT pm.ProgrammeID) AS ProgrammeCount FROM Modules m LEFT JOIN Staff s ON m.ModuleLeaderID=s.StaffID LEFT JOIN ProgrammeModules pm ON m.ModuleID=pm.ModuleID GROUP BY m.ModuleID ORDER BY m.ModuleName")->fetchAll();
$pageTitle='Modules'; include __DIR__.'/includes/admin_header.php'; ?>

<div class="page-header">
    <div class="page-header-left"><div class="ku-divider-sm"></div><h1>Modules</h1><p>Manage all course modules.</p></div>
    <a href="/student_course_hub/admin/modules.php?action=create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Module</a>
</div>
<div class="table-wrap">
    <div class="table-toolbar">
        <span class="table-toolbar-title">All Modules (<?= count($modules) ?>)</span>
        <input type="search" class="table-search" placeholder="Search…" data-table-search="modulesTable">
    </div>
    <table id="modulesTable">
        <thead><tr><th>Module Name</th><th>Leader</th><th>Used In</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if(empty($modules)):?><tr class="empty-row"><td colspan="4">No modules found.</td></tr>
        <?php else: foreach($modules as $mod):?>
        <tr>
            <td><strong><?= h($mod['ModuleName']) ?></strong></td>
            <td class="text-muted text-small"><?= h($mod['LeaderName']??'—') ?></td>
            <td><span class="badge badge-blue"><?= $mod['ProgrammeCount'] ?> programme<?= $mod['ProgrammeCount']!=1?'s':'' ?></span></td>
            <td>
                <div class="table-actions">
                    <a href="/student_course_hub/admin/modules.php?action=edit&id=<?= $mod['ModuleID'] ?>" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <form method="POST" action="/student_course_hub/admin/modules.php?action=delete&id=<?= $mod['ModuleID'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete '<?= h($mod['ModuleName']) ?>'?"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; endif;?>
        </tbody>
    </table>
</div>
<?php include __DIR__.'/includes/admin_footer.php'; ?>