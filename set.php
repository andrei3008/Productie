<?php 
    session_start();
    $invalid_characters = array("$", "%", "#", "<", ">", "|");
    $_SESSION['operator'] = str_replace($invalid_characters, "", $_POST['info']);
    $_SESSION['com_name'] = str_replace($invalid_characters, "", $_POST['nume']);
?>