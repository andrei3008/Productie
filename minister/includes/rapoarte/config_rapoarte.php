<?php
	error_reporting(0);
	// require 'db.php';
	require 'class.db.php';
	require 'class.date.php';
	require 'class.stuff.php';

	define('DB_HOST', 'localhost');
	define('DB_ÚSER', 'shorek');
	define('DB_PASS', 'BudsSql7');
	define('DB_NAME', 'brunersrl');
	define('ROOT', $_SERVER[DOCUMENT_ROOT]);
	define('DOMAIN', 'http://red77.ro');

	$datab = new datab(DOMAIN, DB_ÚSER, DB_PASS, DB_HOST, DB_NAME, array());
	$data_rom = new data_rom();
	$stuff = new stuff();
?>