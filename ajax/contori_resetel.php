<?php
	require_once "../autoloader.php";
	error_reporting(0);
    require_once('../includes/class.db.php');
    require_once "../includes/class.databFull.php";

    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());

    $idAparat = intval($_POST['idAparat']);
    $idlocatie = intval($_POST['idlocatie']);
    $zi = $databFull->sanitize($_POST['zi']);
    $idxInE = $databFull->sanitize($_POST['idxInE']);
    $idxOutE = $databFull->sanitize($_POST['idxOutE']);
    $tip = $databFull->sanitize($_POST['tip']); // -> azi / ieri
    $idxInM = $databFull->sanitize($_POST['idxInM']);
    $idxOutM = $databFull->sanitize($_POST['idxOutM']);

    if ($tip == 'azi') {
    	/****************************************************************************************
    	|	 resetare contori zi curenta														|
    	****************************************************************************************/
    	$array = array($idAparat, date('Y-m-d', strtotime($zi)));
    	$rows = $databFull->getRows('resetcontori', 'count(idresetce) as nr, idresetce', 'WHERE idaparat = ? AND DATE(dtReset) = ? ORDER BY idresetce DESC', $array);
    	if ($rows[0]['nr'] > 0) {
    		// ----------------------------------------------------------------------------------
    		// s-a mai facut o resetare - UPDATE cu noile valori								|
    		// ----------------------------------------------------------------------------------
        		$array = array($idxInE, $idxOutE, $idxInM, $idxOutM, date('Y-m-d H:i:s'), $rows[0]['idresetce']);
        		$update = $databFull->updateRow('resetcontori', 'idxInE=?, idxOutE=?, idxInM=?, idxOutM=?, dtReset=?', 'WHERE idresetce=?', $array);
                 // Salvare log
                $databFull->logsInsertRow($_SESSION['username'], 'UPDATE', 'resetcontori', 'idxInE,idxOutE,idxInM,idxOutM,dtInitiere,dtTerminare', $array, 'WHERE idAparat='.intval($idAparat));
                $err = ($update != 0) ? 0 : 1;
    	} else {
    		// ----------------------------------------------------------------------------------
    		// se face resetare pentru prima data - INSERT										|
    		// InE = ultimul InE de ieri(contorelectronic) + CashInM de azi (contormecanic)		|
    		// OutE = ultimul OutE de ieri(contorelectronic) + CashOutM de azi (contormecanic)	|
    		// ----------------------------------------------------------------------------------
        		$table_electronic = 'contorelectronic'.date('Y', strtotime($zi)).date('m', strtotime($zi));
        		$table_mecanic = 'contormecanic'.date('Y', strtotime($zi)).date('m', strtotime($zi));
                $data_ieri = date('Y-m-d', strtotime($zi.' - 1day'));
        		$data_azi = date('Y-m-d', strtotime($zi));
                $array_ieri = array($idAparat, $data_ieri);
        		$array_azi = array($idAparat, $data_azi);
        		$rows_ieri = $databFull->getRows($table_electronic, 'idxInE, idxOutE', 'WHERE idaparat = ? AND DATE(dtServer) = ? ORDER BY dtServer DESC', $array_ieri);
        		$rows_azi = $databFull->getRows($table_mecanic, 'cashIn, cashOut', 'WHERE idaparat = ? AND DATE(dtServer) = ? ORDER BY dtServer DESC', $array_azi);
                // print_r($array_azi);
                // print_r($rows_azi);
                // echo '<br /> --------------------------- <br />';
                // print_r($array_ieri);
                // print_r($rows_ieri);

                $idxInE = $rows_ieri[0]['idxInE'] + $rows_azi[0]['cashIn']; 
                $idxOutE = $rows_ieri[0]['idxOutE'] + $rows_azi[0]['cashOut']; 

                $array = array($idAparat, $idlocatie, $idxInE, $idxOutE, $idxInM, $idxOutM, date('Y-m-d H:i:s'));
                // print_r($array);
                // echo '<br /> --------------------------- <br />';
                $params = '?, ?, ?, ?, ?, ?, ?';
                $inserted = $databFull->insertRow('resetcontori', 'idaparat, idlocatie, idxInE, idxOutE, idxInM, idxOutM, dtReset', $params, $array);
                $databFull->logsInsertRow($_SESSION['username'], 'INSERT', 'resetcontori', 'idaparat,idlocatie,idxInE,idxOutE,idxInM,idxOutM,dtReset', $array, 'WHERE idAparat='.intval($idAparat));
                $err = ($inserted != 0) ? 0 : 1;
    	}
       
       

    	// print_r($array);
    	// print_r($rows);

    } elseif ($tip == 'ieri') {
    	/****************************************************************************************
    	|	 setare contori zi precedenta														|
    	****************************************************************************************/
        // ----------------------------------------------------------------------------------
        // InE = se updateaza in contorelectronic                                           |
        // OutE = se updateaza in contorelectronic                                          |
        // ----------------------------------------------------------------------------------
        $update = $databFull->updateRow('resetcontori', 'idxInE=?, idxOutE=?, idxInM=?, idxOutM=?, dtReset=?', 'WHERE idresetce=?', $array);
         // Salvare log
        $databFull->logsInsertRow($_SESSION['username'], 'UPDATE', 'resetcontori', 'idxInE,idxOutE,idxInM,idxOutM,dtInitiere,dtTerminare', $array, 'WHERE idAparat='.intval($idAparat));
        $err = ($update != 0) ? 0 : 1;

    }
    $data = array(
        'err' => $err, 
        'mesaj' => (($err == 0) ? 'Date updatate cu succes!' : 'Datele nu s-au putut salva!'),
        'color' => (($err == 0) ? 'green' : 'red'),
    );
    header('Content-Type: application/json');
    echo json_encode($data);
?>