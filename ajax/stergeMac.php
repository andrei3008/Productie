<?php
require_once "../autoloader.php";

$db = new DataConnection();
$appSettings = new SessionClass();

$macMapper = new MacPicMapper($db,$appSettings);

$macMapper->deleteMacByIp("82.79.220.114");