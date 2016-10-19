<?php
require_once 'includes/dbConnect.php';
//$logger = new Logger('logs/logs'.date('Y-m-d').'.txt');
//$logger->writeToFile('Utilizatorul '.$_SESSION['username_redlong']. ' s-a delogat.', 'LOGOUT',  $_SESSION['id_vizita']);
header('location:login.php');