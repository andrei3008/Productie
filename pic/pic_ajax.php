<?php
	include '../includes/class.db.php';
	require_once "../autoloader.php";
	error_reporting(0);
	$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
	$idAparat = intval($_POST[idAparat]);
	$type = $datab->sanitize($_POST[type]); // IN/OUT
	$tip = $datab->sanitize($_POST[tip]); 	// M/E
	// index vechi 
		// $rows = $datab->getRows('stareaparate', 'lastIdx'.$type.'M as indexed', 'WHERE idAparat=?', array($idAparat));
		// $index = $rows[0][indexed];

	//update
	if ($tip == 'M') {
		$rows = $datab->getRows('errorpk', 'idAparat, dataServer, exceptia, index'.$type.'M as index_to_change', 'WHERE exceptia LIKE "%'.$type.' Baza=%" AND idAparat=? ORDER BY idpachet DESC LIMIT 1', array($idAparat));
		$exceptia = $rows[0][exceptia];
		$index_to_change = $rows[0][index_to_change];

		$updated = $datab->updateRow('stareaparate', 'lastIdx'.$type.'M=?', 'WHERE idAparat=?', array($index_to_change, $idAparat));
		if ($updated) {
			echo 'Index'.$type.'M updatat cu succes!';
		} else {
			echo 'Index'.$type.'M nu s-a modificat!';
		}
		// index nou
			// $rows = $datab->getRows('stareaparate', 'lastIdx'.$type.'M as indexed', 'WHERE idAparat=?', array($idAparat));
			// $index = $rows[0][indexed];
			// echo 'Index vechi '.$type.' = '.$index.'- index nou = '.$index;
		// print_r($rows);
	} elseif ($tip == 'E') {
		$rows = $datab->getRows('errorpk', 'idAparat, dataServer, exceptia, index'.$type.'E as index_to_change', 'WHERE idAparat=? ORDER BY idpachet DESC LIMIT 1', array($idAparat));
		$exceptia = $rows[0][exceptia];
		$index_to_change = $rows[0][index_to_change];
		$updated = $datab->updateRow('stareaparate', 'lastIdx'.$type.'E=?', 'WHERE idAparat=?', array($index_to_change, $idAparat));
		if ($updated) {
			echo 'Index'.$type.'E updatat cu succes!';
		} else {
			echo 'Index'.$type.'E nu s-a modificat!';
		}
	}
?>