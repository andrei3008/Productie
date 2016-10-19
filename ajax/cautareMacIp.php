<?php
    require_once "../includes/_db.inc.php";
    require_once "../classes/PageClass.php";
    error_reporting(0);
    require_once "../includes/class.db.php";
    $datab = new datab(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $ip = $datab->sanitize($_POST['ip']);
    $mac = $datab->sanitize($_POST['mac']);
    $idAparat = intval($_POST['idAparat']);
    $seria = $datab->sanitize($_POST['seria']);
    $judet = $datab->sanitize($_POST['judet']);
    $adresa = $datab->sanitize($_POST['adresa']);
    $responsabil = $datab->sanitize($_POST['responsabil']);

    if (($mac != '') && ($ip != '')) {
    	$where = 'AND s.ipPic=? AND  s.macPic=?';
    	$array = array($ip, $mac);
    } elseif (($mac != '') && ($ip == '')) {
    	$where = 'AND s.macPic=?';
    	$array = array($mac);
    } elseif (($mac == '') && ($ip != '')) {
    	$where = 'AND s.ipPic=?';
    	$array = array($ip);
    } else {
    	$where = '';
    	$array = array();
    }
    if ($idAparat  && ($idAparat != 0)) {
    	$where .= 'AND s.idAparat=?';
    	$array[] = $idAparat;
    }
    if ($seria  && ($seria != '')) {
    	$where .= 'AND a.seria LIKE ?';
    	$array[] = '%'.$seria.'%';
    }
	if ($judet  && ($judet != '')) {
    	$where .= 'AND l.regiune LIKE ?';
    	$array[] = '%'.$judet.'%';
    }
    if ($adresa  && ($adresa != '')) {
    	$where .= 'AND l.adresa LIKE ?';
    	$array[] = '%'.$adresa.'%';
    }
 	if ($responsabil  && ($responsabil != '')) {
    	$where .= 'AND p.nick LIKE ?';
    	$array[] = '%'.$responsabil.'%';
    }

    $query = "SELECT
				s.idAparat,
				s.ipPic,
    			s.macPic,
			    a.seria,
			    l.idfirma,
			    l.denumire,
			    l.idOperator,
			    p.nick,
			    s.bitiComanda,
			    s.stareRetur,
			    s.lastIdxInM,
			    s.lastIdxOutM,
			    s.ultimaConectare,
			    s.idApRetur,
			    a.dtActivare,
			    a.dtBlocare,
			    l.denumire,
			    l.adresa,
			    l.dtInfiintare,
			    logs.data
			from
				stareaparate s  LEFT JOIN
			    (
				  SELECT log.data, log.ipPic, log.macPic 
				        FROM logssetpic log
				        WHERE 
				   (idApPic = 0 OR idApPic IS NULL) AND
				   macPic LIKE '%".$mac."%'
				  ORDER BY log.data DESC LIMIT 1
			    ) logs ON s.ipPic = logs.ipPic AND s.macPic = logs.macPic,
			    aparate a,
			    locatii l,
			    personal p 
			where
				s.idAparat=a.idAparat AND
			    a.idLocatie=l.idlocatie AND
			    l.idresp=p.idpers {$where}";
	// echo $query;
	// print_r($array);
    $stmt = $datab->datab->prepare($query); 
    $stmt->execute($array);
    $result = $stmt->fetchAll();  
?>
<table class="table table-bordered table-responsive" id="dataTables">
	<thead>
	    <tr>
	        <th style="width: 50px">#</th>
	        <th style="width: 80px">Seria</th>
	        <th>Locatie</th>
	        <th>Adresa</th>
	        <th style="width: 100px">Resp.</th>
	        <th style="width: 140px">Mac</th>
	        <th style="width: 140px">Ip</th>
	        <th style="width: 160px">Data deschidere loc</th>
	        <th style="width: 160px">Data inserare MAC</th>
	    </tr>
	</thead>
	<tbody>
	    <?php

	    	foreach ($result as $key => $val) {
	    		$dataInserare = '';
	    		$i = $key+1;
	    		$dataInfiintare = date('d', strtotime($val[dtInfiintare])).' '.(PageClass::getLuna(date('n', strtotime($val[dtInfiintare])))).' '.date('Y', strtotime($val[dtInfiintare]));
	    		if ($val[data]) {
	    			$dataInserare = date('d', strtotime($val[data])).' '.(PageClass::getLuna(date('n', strtotime($val[data])))).' '.date('Y', strtotime($val[data])).' '.date('H:i:s', strtotime($val[data]));
	    		}
	    		
	    		echo "
	    			<tr>
	    				<td> {$i} </td>
	    				<td> {$val[seria]} </td>
	    				<td> {$val[denumire]} </td>
	    				<td> {$val[adresa]} </td>
	    				<td> {$val[nick]} </td>
	    				<td> {$val[macPic]} </td>
	    				<td> {$val[ipPic]} </td>
	    				<td> {$dataInfiintare} </td>
	    				<td > {$dataInserare} </td>
	    			</tr>
	    		";
	    	}
	    ?>
    </tbody>
</table>
<style type="text/css">
	tr td:last-child {
		text-align: right;
	}
	.table > tbody > tr > td {
		padding: 4px 9px
	}
	.DataTables_sort_wrapper {
		text-align: center;
	}
</style>