<?php
	
	require_once "../autoloader.php";
	error_reporting(E_ALL);
	require_once('../includes/class.db.php');
	$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
	
	$valoare = intval($_POST['valoare']);
	$zi = $datab->sanitize($_POST['zi']);
	$type = $datab->sanitize($_POST['type']);
	$luna2 = (strlen(intval($_POST['luna'])) == 1) ? '0'.intval($_POST['luna']) : intval($_POST['luna']);
	$luna = intval($_POST['luna']);
	$an = intval($_POST['an']);
	$idAparat = intval($_POST['idAparat']);
	$id_tabel = intval($_POST['id_tabel']);
	
	$options = array(
		'idxInM' => array('tabel' => 'contormecanic2016'.$luna, 'col' => 'idxInM', 'cash' => 'cashIn', 'id' => 'idmec', 'stareaparate' => 'lastIdxInM'),
		'idxOutM' => array('tabel' => 'contormecanic2016'.$luna, 'col' => 'idxOutM', 'cash' => 'cashOut', 'id' => 'idmec', 'stareaparate' => 'lastIdxOutM'),
		'idxInE' => array('tabel' => 'contorelectronic2016'.$luna, 'col' => 'idxInE', 'cash' => 'cashIn', 'id' => 'idel', 'stareaparate' => 'lastIdxInE'),
		'idxOutE' => array('tabel' => 'contorelectronic2016'.$luna, 'col' => 'idxOutE', 'cash' => 'cashOut', 'id' => 'idel', 'stareaparate' => 'lastIdxOutE')
	);
	$array = array(date("Y-m-d", strtotime($an."-".$luna2.'-'.$zi.' -1 days')), $idAparat);
	$indexi_ieri = $datab->getRows($options[$type]['tabel'], $type.', '.$options[$type]['id'].' as id' , 'WHERE DATE(dtServer) = ? AND idAparat=? ORDER BY dtPic DESC LIMIT 1', $array);
	$id_contor_ieri = $indexi_ieri[0]['id'];
	$index_prec = ($indexi_ieri[0][$type]) ? $indexi_ieri[0][$type] : $valoare;
	$diff = $valoare - $index_prec;


	// print_r($array);
	// echo 'diff = '.$diff;
	$array_curent_index = array($valoare, $id_tabel);
	$array_curent_cash = array($diff, $id_tabel);
	$array_curent_castig = array($id_tabel);
	$array_curent_stareaparate = array($valoare, $idAparat);
	// print_r($array_curent_index);
	$update = $datab->updateRow($options[$type]['tabel'], $type.' = ? ', 'WHERE '.$options[$type]['id'].' = ?', $array_curent_index);
	$update = $datab->updateRow($options[$type]['tabel'], $options[$type]['cash'].' = ? ', 'WHERE '.$options[$type]['id'].' = ?', $array_curent_cash);
	$update = $datab->updateRow($options[$type]['tabel'], 'castig = cashIn - cashOut', 'WHERE '.$options[$type]['id'].' = ?', $array_curent_castig);

	// DACA E ZIUA CURENTA SE UPDATEAZA SI stare_aparate
	if (strtotime($an."-".$luna2.'-'.$zi) == strtotime(date('d-m-Y'))) {
		$update = $datab->updateRow('stareaparate', $options[$type]['stareaparate'].' = ?', 'WHERE idAparat = ?', $array_curent_stareaparate);
	}
	// if (condition) {
		// code...
	// }
?>