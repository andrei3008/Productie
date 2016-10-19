<?php
	include '../includes/class.db.php';
	require_once "../autoloader.php";
	error_reporting(0);
	$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
	$seria = $datab->sanitize($_POST[seria]);
	$idmac = intval($_POST[idmac]);
	// index vechi 
		// $rows = $datab->getRows('stareaparate', 'lastIdx'.$type.'M as indexed', 'WHERE idAparat=?', array($idAparat));
		// $index = $rows[0][indexed];

	// id aparat
		$rows = $datab->getRows('aparate', 'idAparat', 'WHERE seria=? ORDER BY idAparat DESC LIMIT 1', array($seria));
		$idAparat = $rows[0][idAparat];

	// date macpicneasociaat
		$rows = $datab->getRows('macpicneasociat', 'idxInM, idxOutM', 'WHERE idmacpic=?', array($idmac));
		$idxInM = $rows[0][idxInM];
		$idxOutM = $rows[0][idxOutM];

	// updatare indecsi
		$updated1 = $datab->updateRow('stareaparate', 'lastIdxInM=?', 'WHERE idAparat=? AND lastIdxInM < '.$idxInM, array($idxInM, $idAparat));
		$updated2 = $datab->updateRow('stareaparate', 'lastidxOutM=?', 'WHERE idAparat=? AND lastIdxOutM < '.$idxInM, array($idxOutM, $idAparat));
		$out = '';
		if ($updated1) {
			$out .= "lastIdxInM updatat cu succes! \n";
		} elseif ($updated2) {
			$out .= "lastidxOutM updatat cu succes!";
		} else {
			$out .= "Indecsii nu s-au modificat!";
		}
		echo $out;
	// index nou
		// $rows = $datab->getRows('stareaparate', 'lastIdx'.$type.'M as indexed', 'WHERE idAparat=?', array($idAparat));
		// $index = $rows[0][indexed];
		// echo 'Index vechi '.$type.' = '.$index.'- index nou = '.$index;
	// print_r($rows);
?>