<?php

$lifetime = 600;
session_set_cookie_params($lifetime);
session_start();
$dbHost = "localhost";
$dbUser = "shorek";
$dbPass = "BudsSql7";
include '../../includes/_db.inc.php';
// $con = new mysqli($dbHost, $dbUser, $dbPass);
$con = new mysqli(DB_HOST, DB_USER, DB_PASS);
require_once 'functions/Logger.php';

if ($con->connect_errno) {
    echo "A aparut o eroare la conexiunea cu baza de date. " . $con->connect_error;
    exit();
}

function aparatePerOperator($operator, $con) {
    $query = "SELECT "
            . "COUNT(aparate.idAparat) AS nr_aparate"
            . " FROM brunersrl.aparate "
            . " INNER JOIN brunersrl.locatii on aparate.idLocatie = locatii.idlocatie "
            . " WHERE locatii.idOperator='$operator' AND dtBlocare='1000-01-01'";
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj->nr_aparate;
}
