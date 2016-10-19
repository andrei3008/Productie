<?php

require_once '../includes/dbConnect.php';
require_once '../includes/functions/Logger.php';
$logger = new Logger('../logs/logs' . date('Y-m-d') . '.txt');
$logger->writeToFile('Utilizatorul ' . $_SESSION['username_ampera'] . ' s-a delogat de pe aplicatie', "LOGOUT", $_SESSION['id_vizita']);
session_unset();
session_destroy();
header('location:login.php');
