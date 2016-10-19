<?php

require_once "../autoloader.php";

$db = new DataConnection();
$appSettings = new SessionClass();
$expirariMapper = new ExpirariMapper($db, $appSettings);


$regiuni = [];
foreach ($_POST['luni'] as $luna) {
    $regiuniLunare = $expirariMapper->getAparateLuna($luna,$_POST['operator']);
    foreach ($regiuniLunare as $regiuniL) {
        $regiuni[] = $regiuniL;
    }
}

$regiuni = array_unique($regiuni);
asort($regiuni);
foreach ($regiuni as $region) {
    echo "<option value='{$region}'>{$region}</option>";
}
?>

