<?php

require_once "../autoloader.php";

$dbFull = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

$db = new DataConnection();

$appSettings = new SessionClass();

$page = new PageClass();

$locatiiMapper = new LocatiiMaper($db, $appSettings);
$locatie = new LocatiiEntity($db, $appSettings);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Verificare tehnica</title>
    <?php
        require_once "../includes/header.php";
    ?>
</head>
<body>
    <?php require_once "../includes/menu.php"; ?>
    <div class="container">
        <div class="row">

        </div>
    </div>
</body>
</html>
