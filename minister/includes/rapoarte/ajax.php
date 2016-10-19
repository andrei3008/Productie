<?php
	include 'config_rapoarte.php';
/*--------------------------------------------------
	Incarcare zile luna curenta sau luna selectata
--------------------------------------------------*/
	include 'class.rapoarte.php';
	include 'class.entity-incasare.php';
	$rapoarte = new rapoarte($datab, $data_rom);
	if (isset($_POST[type]) && ($_POST[type] == 'nume_firma')) {
		echo $rapoarte->get_nume_firma(array('id' => $_POST[id]));
	}
	if (isset($_POST[type]) && ($_POST[type] == 'nume_locatie')) {
		echo $rapoarte->get_nume_locatie(array('id' => $_POST[id]));
	}

	if (isset($_POST[type]) && ($_POST[type] == 'load_zile_luna')) {
		echo $rapoarte->load_zile_luna(
								array(
									'an' => $_POST[an], 
									'luna' => $_POST[luna], 
									'firma' => intval($_POST[firma]), 
									'locatie' => intval($_POST[locatie]),
									'tip'=> $_POST[tip]
								)
						);
	}

/*--------------------------------------------------
	Generare SITUATIE SIZ-SIL
--------------------------------------------------*/
	if (isset($_POST[type]) && ($_POST[type] == 'generare-raport')) {
		// error_reporting(E_ALL);
		echo $rapoarte->generare_raport(
								array(
									'data_select' => $_POST[data_select], 
									'firma_select' => intval($_POST[firma_select]),
									'firma' => intval($_POST[firma]), 
									'locatie' => intval($_POST[locatie]),
									'tip'=> $_POST[tip],
									'operator'=> $_POST[operator]
								)
						);
	}

/*--------------------------------------------------
	Generare SITUATIE ZILNICA - SIL
--------------------------------------------------*/
	if (isset($_POST[type]) && ($_POST[type] == 'genereaza_export')) {
		// error_reporting(E_ALL);
		include 'class.export.php';
		include "../../../classes/mpdf/mpdf.php";
		include '../../../classes/PHPExcel.php';
		include '../../../classes/PageClass.php';
		$pdf = new mPDF('c', 'A4-L');
		$excel = new PHPExcel();
		$export = new export($datab, $stuff, $_POST, $rapoarte, $pdf, $excel);
		echo $export->generare_export();
	}
?>