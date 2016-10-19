<?php
require_once('classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$page = new PageClass();
$session = new SessionClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
?>
<div class="buttons">
    <input type="file" name="poza"/>
    <a href="#" class="inchidePanel">Inchide Fereastra</a>
    <?php

    ?>
</div>
