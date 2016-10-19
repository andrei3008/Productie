<?php

require_once 'includes/dbConnect.php';
$logger = new Logger('logs/logs' . date('Y-m-d') . '.txt');
$logger->writeToFile('Utilizatorul ' . $_SESSION['username_ampera'] . ' s-a delogat.', 'LOGOUT', $_SESSION['id_vizita']);
session_destroy();
header('location:login.php');
