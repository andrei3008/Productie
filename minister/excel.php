<?php
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
require_once('../classes/PHPExcel.php');
$db = new dbFull("localhost",'shorek','BudsSql7');

$page = new PageClass();

$page->checkLogin($_SESSION['username'],$_SESSION['operator']);


$document = new PHPExcel();

$document->setActiveSheetIndex(0);

$rowCount = 2;


$aparate = $db->getAllAparate(1);
$document->getActiveSheet()->setCellValue('A1','Denumirea organizatorului');
$document->getActiveSheet()->setCellValue('B1','Locație (Județ, Localitate, Adresă)');
$document->getActiveSheet()->setCellValue('C1','Serie mijloc de joc');
$document->getActiveSheet()->setCellValue('D1','Contor total intrări');
$document->getActiveSheet()->setCellValue('E1','Contor total ieșiri');
$document->getActiveSheet()->setCellValue('F1','Acumulare Jackpot');
$document->getActiveSheet()->setCellValue('G1','Stare aparat (pornit/oprit)');
$document->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold(true);

$sheet = $document->getActiveSheet();
$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(true);
/** @var PHPExcel_Cell $cell */
foreach ($cellIterator as $cell) {
    $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
}
$document->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
$document->getActiveSheet()->getColumnDimension('B')->setWidth(100);

foreach($aparate as $aparat){
    $document->getActiveSheet()->setCellValue('A'.$rowCount,'S.C. Ampera Games S.R.L.');
    $document->getActiveSheet()->setCellValue('B'.$rowCount,$aparat->adresa);
    $document->getActiveSheet()->setCellValue('C'.$rowCount,$aparat->seria.' ');
    $document->getActiveSheet()->setCellValue('D'.$rowCount,$aparat->lastIdxInM);
    $document->getActiveSheet()->setCellValue('E'.$rowCount,$aparat->lastIdxOutM);
    $document->getActiveSheet()->setCellValue('F'.$rowCount,'0');
    $document->getActiveSheet()->setCellValue('G'.$rowCount,'Oprit');
    $rowCount++;
}


$writer = new PHPExcel_Writer_Excel5($document);
$writer->save('aparate.xls');
header('location:aparate.xls');