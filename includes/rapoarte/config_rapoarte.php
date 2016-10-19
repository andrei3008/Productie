<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/_db.inc.php';
	error_reporting(E_ALL);
	
	require_once ROOT.'/includes/rapoarte/class.date.php';
	require_once ROOT.'/includes/rapoarte/class.stuff.php';
	if (!class_exists('datab')) {
		require_once ROOT.'/includes/rapoarte/class.db.php';
	    $datab = new datab(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
	}
	
	$data_rom = new data_rom();
	$stuff = new stuff();
?>