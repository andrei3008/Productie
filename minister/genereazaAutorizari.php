<?php
require_once '../includes/dbFull.php';
require_once '../classes/PageClass.php';
require_once '../classes/PHPExcel.php';
$db = new dbFull("localhost","shorek","BudsSql7");
$page = new PageClass();

$excelDocument = new PHPExcel();

$excelDocument->setActiveSheetIndex(0);

/*
 * Se preia aparatele ce au data de expirare pe luna data
 */
$autorizatii = $db->getAvertizariByLuna('dtExpAutorizatie',date('Y'),date('n'),1);


/**
 * Document header
 */
$excelDocument->getActiveSheet()->setCellValue('A1','Nr. Crt.');
$excelDocument->getActiveSheet()->setCellValue('B1','Denumirea organizatorului');
$excelDocument->getActiveSheet()->setCellValue('C1','Locatie (Judet, Localitate, Adresa)');
$excelDocument->getActiveSheet()->setCellValue('D1','Serie de joc');
$excelDocument->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

/*
 * Autozise cell row;
 */
$sheet = $excelDocument->getActiveSheet();
$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(true);
/** @var PHPExcel_Cell $cell */
foreach ($cellIterator as $cell) {
    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
}
/*
 * End autosize
 */
$i=2;

/**
 * Se cicleaza prin aparate
 */
foreach($autorizatii as $autorizatie){
    $excelDocument->getActiveSheet()->setCellValue('A'.$i,$i-1);
    $excelDocument->getActiveSheet()->setCellValue('B'.$i,'S.C. Ampera Games S.R.L.');
    $excelDocument->getActiveSheet()->setCellValue('C'.$i,$autorizatie->regiune. ' '.$autorizatie->adresa.' ');
    $excelDocument->getActiveSheet()->setCellValue('D'.$i,$autorizatie->seria);
    $i++;
}

/*
 * Se scrie in document
 */
$excelWriter = new PHPExcel_Writer_Excel5($excelDocument);

$excelWriter->save('AutorizatiiScAmperaGamesSRL'.date('m').'.xls');

header('location:AutorizatiiScAmperaGamesSRL'.date('m').'.xls');