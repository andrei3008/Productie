<?php
    require_once "autoloader.php";
    
    require_once 'classes/SessionClass.php';
    require_once "includes/class.db.php";
    require_once "includes/class.databFull.php";
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $session =  $appSettings = new SessionClass();
    $page = new PageClass();
    $page->checkLogin($session->getUsername(), $session->getOperator());

    // $thiss = true;
    // $that = false;

    // var_dump($truthiness = $thiss and $that);
    $type = (isset($_GET['type'])) ? $_GET['type'] : 'culoareAparat';
    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'DESC';
    $tip_sortare = 'ord';
    $Operator = ($session->getOperator()) ? $session->getOperator() : 1;
    $Idresp = ($session->getIdresp()) ? $session->getIdresp() : 5;
    $an = (isset($an)) ? $an : date('Y');
    $luna = (isset($luna)) ? $luna : date('m');
    $rows = $databFull->getLocatiiResponsabil($Operator, $Idresp, $an, $luna, $type, $sort, $tip_sortare);
    $rows2 = $databFull->getErrorsByPers($Idresp, $Operator);
    print_r($rows);
?>