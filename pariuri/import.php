<?php
require_once "../autoloader.php";
require_once '../classes/PHPExcel/IOFactory.php';

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);

$file = 'pariuri.xls';
$pariuriMapper = new PariuriMapper($db);

try{
    $inputFileType = PHPExcel_IOFactory::identify($file);
    $cititor = PHPExcel_IOFactory::createReader($inputFileType);
    $excel = $cititor->load($file);
}catch (Exception $e){
    die("Nu s-a putut citi fisierul ".$file. 'Informatii suplimentare '.$e->getMessage());
}

$responsabili = [
    'mirabela' => 1,
    'Dani HD' => 15,
    'Martin' => 6,
    'Mihaela Blonda' => 5,
    'Marius VL' => 16,
    'Iulian' => 3,
    'Nelu' => 2,
    'Cocos' => 599,
    'Vanta' => 603,
    'Filip' => 602,
    'Coliban' => 593,
    'Manex' => 594,
    'Marios' => 595,
    'Tariverde' => 597,
    'Oberkapo' => 596,
    'Mester'    => 601,
    'Nicmus'    => 600,
    'Desdemona' => 598,
    'Ducu'  => 4,
    'Cristina moise' => 14
];
var_dump($responsabili);
$numarSheets = $excel->getSheetCount();
$numeResponsabili = $excel->getSheetNames();
for($i = 1; $i<$numarSheets; $i++){
    $pagina = $excel->getSheet($i);
    $numarRanduri = $pagina->getHighestRow();
    $numarColoane = 3;
    for($rand = 1; $rand <= $numarRanduri; $rand++){
        $informatiiRand = $pagina->rangeToArray('A'.$rand.':C'.$rand,NULL,TRUE,FALSE);
        if($informatiiRand[0][0] != "Denumire") {
            $locatiePariu = new PariuriEntity();


            $locatiePariu->setAdresa($informatiiRand[0][2]);
            $locatiePariu->setAnalitic($informatiiRand[0][1]);
            $locatiePariu->setDenumire($informatiiRand[0][0]);

            $id = $responsabili[$numeResponsabili[$i]];
            if(!$pariuriMapper->locatieExists($locatiePariu)) {
                $locatiePariu->setIdPersonal($id);
                var_dump($locatiePariu);
                $pariuriMapper->insertLocatie($locatiePariu);
            }
        }
    }
}