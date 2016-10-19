<?php
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
$dbScrie = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$page = new PageClass();
$post = $db->sanitizePost($_POST);
if($dbScrie->updateNumarPrincipalAngajati($post['idVechi'],$post['idNou'],$post['telefon'])){
    echo $page->printDialog('success','Numarul principal modificat cu success!');
}else{
    echo $page->printDialog('danger','Ceva nu a mers bine va rugam sa contactati echipa tehnica');
}
