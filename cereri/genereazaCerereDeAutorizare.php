<?php
require_once "../autoloader.php";

$session = new SessionClass();
$session->exchangeArray($_SESSION);


$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$page = new PageClass();

$database = new DataConnection();

$page->checkLogin($session->getUsername(),$session->getOperator());
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Generare cereri autorizatii</title>
        <?php require_once "../includes/header.php"; ?>
    </head>
<body lang="ro">
    <?php require_once "../includes/menu.php"; ?>


</body>
</html>
