<?php require_once "autoloader.php"; ?>
<?php require_once('classes/SessionClass.php'); ?>
<?php
    require_once('includes/dbFull.php');
    $appSettings = $session = new SessionClass();
    $page = new PageClass();
?>
<!DOCTYPE html>
<html>
<hea>
    <title>Lista modficari</title>
    <?php require_once('includes/header.php'); ?>
</hea>
<body>
<?php require_once('includes/menu.php'); ?>
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">Lista Evenimente</div>
        <div clsas="panel-body table-responsive">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Nr. Crt.</th>
                    <th>User</th>
                    <th>Tip Interactiune</th>
                    <th>Sql</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                <?php $sqlLogs = $db->getSqlLogs();
                $i=1;
                foreach ($sqlLogs as $log) {
                    ?>
                    <tr>
                        <td><?php echo $i; $i++; ?></td>
                        <td><?php echo $log->user; ?></td>
                        <td><?php echo $log->eveniment; ?></td>
                        <td><?php echo $log->statement; ?></td>
                        <td><?php echo $log->data; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
