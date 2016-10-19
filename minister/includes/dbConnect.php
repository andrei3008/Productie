<?php
session_start();
$dbHost = "localhost";
$dbUser = "adi";
$dbPass = "adi77";
$con = new mysqli($dbHost, $dbUser, $dbPass);
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
            . " WHERE locatii.idOperator='$operator' AND aparate.dtBlocare='1000-01-01'";
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj->nr_aparate;
}