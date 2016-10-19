<?php
//
//require_once "../autoloader.php";
//require_once "../classes/PHPExcel/IOFactory.php";
//
//
//$db = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
//
//$file = "csv.xls";
//
//$pariuriMapper = new PariuriMapper($db);
//
//try {
//    $inputFileType = PHPExcel_IOFactory::identify($file);
//    $cititor = PHPExcel_IOFactory::createReader($inputFileType);
//    $excel = $cititor->load($file);
//} catch (Exception $e) {
//    die("Nu s-a putut deschide fisierul " . $file . ' deoarece ' . $e->getMessage());
//}
//$informatiiCompelete = [];
//$pagina = $excel->getSheet();
//$nrRanduri = $pagina->getHighestRow();
//for ($linie = 14; $linie < $nrRanduri; $linie++) {
//    $infoRand = $pagina->rangeToArray('A' . $linie . ":G" . $linie, NULL, TRUE, FALSE);
//    $informatiiCompelete[$linie]=[
//        'dataTranzactie'       => $infoRand[0][0],
//        'dataValuta'           => $infoRand[0][1],
//        'descriere'             => explode(';',$infoRand[0][2]),
//        'referintaTranzactie'   => $infoRand[0][3],
//        'debit'                 => $infoRand[0][4],
//        'credit'                => $infoRand[0][5],
//        'soldContabil'          => $infoRand[0][6]
//    ];
//}
//var_dump($informatiiCompelete);