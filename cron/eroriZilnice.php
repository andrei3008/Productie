<?php

$upOneLevel = realpath(__DIR__.'/..');


require_once $upOneLevel.'/includes/dbFull.php';
require_once $upOneLevel.'/classes/FileClass.php';


$file = new FileClass('d:\Sorin\webapps\htdocs\ampera\cron\numarErori.txt');
$file->deleteFileContent();
$erori = $db->getArrayErroriResponsabil();

print_r($erori);



foreach($erori as $key => $value){
    $file->writeToFile($key.','.$value."\r\n");
}

