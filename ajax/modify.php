<?php
require_once('classes/SessionClass.php');
require_once '../includes/dbFull.php';
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
if(is_numeric($_POST['loc']) AND (strlen($_POST['pass'])>=4)) {
    if (!$db->setNewUserPassword('user'.$_POST['loc'], $_POST['pass'])){
        echo "<h3>Parola noua atribuita cu success</h3>";
    }else{
        echo "<h3>O eroare a aparut in timpul prelucrarii datelor. Va rugam sa reincercati!</h3>";
    }
}else{
    echo "Parola trebuie sa fie compusa din minim 4 caractere!";
}