<?php
require_once('classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
$page = new PageClass();
$post  = $db->sanitizePost($_POST);
$result = $db->insertInventar($post['idLocatie'],$post['nume'],$post['cantitate'],$post['stare'],$post['observatii'],$_SESSION['username']);
if($result){
    echo $page->printDialog('success','Element de inventar adaugat cu success');
}else{
    echo $page->printDialog('danger','A aparut o eroare va rugam sa contactati echipa de suport tehnic.');
}
?>