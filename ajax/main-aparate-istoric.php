<?php
	include '../autoloader.php';
	include '../includes/class.db.php';
	include '../includes/main.class.php';
	error_reporting(0);
	$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
	$homepage = new Home($datab);
	$idAparat = intval($_POST[idAparat]);
	$seriaAparat = $datab->sanitize($_POST[seriaAparat]);
	$istoric = $homepage->get_istoric_aparate(array('idAparat' => $seriaAparat));
?>
	<table class="table table-bordered table-hover centered" style="border: 1px solid">  
	  	<thead>
		    <tr>
		      	<th rowspan="2">Nr. crt.</th>
		      	<th rowspan="2">idAparat</th>
		      	<th colspan="2">Locatie Veche</th>
		      	<th colspan="2">Locatie Noua</th>
		      	<th rowspan="2">idxInM</th>
		      	<th rowspan="2">idxOutM</th>
		      	<th rowspan="2">dtActivare</th>
		      	<th rowspan="2">dtBlocare</th>
	    	</tr>
	    	<tr>
	    		<td style="width: 120px">Nume</td>
	    		<td style="width: 300px">Adresa</td>
	    		<td style="width: 120px">Nume</td>
	    		<td style="width: 300px">Adresa</td>
	    	</tr>
	  	</thead>
		<tbody>
<?php
			$i = 1;
			foreach ($istoric as $key => $val) {
				$active = ($i == 1) ? 'active' : '';

?>
				<tr class="<?php echo $active;?>">
					<td><?php echo $i;?></td>
					<td><?php echo $val['idAparat'];?></td>
					<td><?php echo $val['loc_vechi_denumire'];?></td>
					<td><?php echo $val['loc_vechi_adresa'];?></td>
					<td><?php echo $val['loc_nou_denumire'];?></td>
					<td><?php echo $val['loc_nou_adresa'];?></td>
					<td><?php echo $val['idxInM'];?></td>
					<td><?php echo $val['idxOutM'];?></td>
					<td><?php echo date('d M Y', strtotime($val['dtActivare']));?></td>
					<td><?php echo ($val['dtBlocare'] == '1000-01-01') ?  '-' : date('d M Y', strtotime($val['dtBlocare']));?></td>
				</tr>
<?php
				$i++;
			}	
?>
		</tbody>
	</table>
