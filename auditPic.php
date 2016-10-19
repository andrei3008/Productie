<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
$appSettings = $session = new SessionClass();
$page = new PageClass();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Interactiuni PIC</title>
        <?php require_once('includes/header.php'); ?>
    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div clas="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">Lista interactiuni cu PIC</div>
                <div class="panel-body">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Nr.Crt</th>
                                <th>Nick</th>
                                <th>Serie Aparat</th>
                                <th>Parametrii</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $audite = $db->getAudite();
                                $i=1;
                                foreach ($audite as $audit) {
                                ?>
                            <tr>
                                <td><?php echo $i;$i++; ?></td>
                                <td><?php echo $audit->nick; ?></td>
                                <td><?php echo $audit->seria; ?></td>
                                <td><?php echo $audit->postPic; ?></td>
                                <td><?php echo $page->afiseazaData($audit->data) ?></td>
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