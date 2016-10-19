<?php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

require_once "../autoloader.php";
require_once "../classes/vendor/autoload.php";

$appSettings = new SessionClass();
$page = new PageClass();

$db = new DataConnection();

$word = new PHPWord();

$locatie = new LocatiiEntity($db, $appSettings);
$locatie->getLocatieCurenta();

$firma = $locatie->getFirma();

$operator = new OperatorEntity($db, $appSettings);
$operator->getCurrentOperator();

$section = $word->addSection([
    "orientation" => "landscape",
    "marginTop" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
    "marginLeft" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
    "marginRight" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
    "marginBottom" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2)
]);
$font = [
    "name" => "Times New Romal",
    "size" => 10
];

$tableStyle = [
    "boercolor" => "000000",
    "borderSize" => 6,
];

$centered = ["align" => "center"];

$aparate = $locatie->getAparateLocatie();
/** @var AparatEntity $aparat */
foreach($aparate as $aparat){
    $aparat->getContoriZilnici($appSettings->getAn(),$appSettings->getLuna());
}

$nrZile = $appSettings->getDaysInCurrentMonth();
for ($zi = 1; $zi <= $nrZile; $zi++) {
    $word->addTableStyle("aparateTable{$zi}", $tableStyle);
    $table = $section->addTable("aparateTabel{$zi}");
    $table->addRow();

    $headerInfo = "Organizatie : {$operator->getDenFirma()}<w:br/>
Licenta de organizare activitate slot machine : {$operator->getLicenta()}<w:br/>
Adresa punctului de lucru : {$locatie->getAdresa()}";
    $data = "<w:br/><w:br/>{$appSettings->getDataDecorata($zi)}";
    /** Table Header Info */
    $table->addCell(13000,["gridspan" => 15])->addText($headerInfo,$font);
    $table->addCell(4838,["gridspan" => 4])->addText($data,$font);




    /** Table disclaimer */
    $table->addRow();
    $table->addCell(17838,["gridspan" => 19])->addText("SITUATIA INCASARILOR (VENITURILOR) ZILNICE) <w:br/>obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)",$font,$centered);

    $table->addRow();
    $table->addCell(300)->addText("Nr. Crt.",$font, $centered);
    $table->addCell(1800)->addText("Seria mijlocului de joc",$font, $centered);
    $table->addCell(1900,['gridspan' => 3])->addText("Indexul contoarelor la inceput (Si)",$font, $centered);
    $table->addCell(1900,['gridspan' => 3])->addText("Indexul contoarelor la sfarsit (Sf)",$font, $centered);
    $table->addCell(1900,['gridspan' => 3])->addText("Factor de multiplicare (F)",$font, $centered);
    $table->addCell(2300,['gridspan' => 3])->addText("Diferenta dintre indexurile contoarelor (D) = (Sf - Si x F)",$font, $centered);
    $table->addCell(2000)->addText("Soldul impulsurilor",$font, $centered);
    $table->addCell(500)->addText("Pret  / Impuls",$font, $centered);
    $table->addCell(1200)->addText("Taxa de participare colectata",$font, $centered);
    $table->addCell(1200)->addText("Total plati efectuate de catre jucatori",$font, $centered);
    $table->addCell(1838)->addText("Incasari (venituri) (lei)",$font, $centered);

    $table->addRow();
    $table->addCell()->addText('',$font, $centered);
    $table->addCell()->addText('',$font, $centered);
    $table->addCell(900)->addText('I',$font, $centered);
    $table->addCell(200)->addText("Ej",$font, $centered);
    $table->addCell(900)->addText('Ei',$font, $centered);
    $table->addCell(900)->addText('I',$font, $centered);
    $table->addCell(200)->addText('Ej',$font, $centered);
    $table->addCell(900)->addText('Ei',$font, $centered);
    $table->addCell(900)->addText('I', $font,$centered);
    $table->addCell(200)->addText('Ej',$font, $centered);
    $table->addCell(900)->addText('Ei',$font, $centered);
    $table->addCell(900)->addText('I',$font, $centered);
    $table->addCell(200)->addText('Ej',$font, $centered);
    $table->addCell(900)->addText('Ei',$font, $centered);
    $table->addCell()->addText('=11 - 12 -13', $font,$centered);
    $table->addCell()->addText('lei', $font,$centered);
    $table->addCell()->addText("= 11 * 15",$font, $centered);
    $table->addCell()->addText("= 13 * 15",$font, $centered);
    $table->addCell()->addText("= 14 * 15 = 16 - 17",$font, $centered);

    $table->addRow();
    $table->addCell()->addText("0",$font, $centered);
    $table->addCell()->addText("1",$font, $centered);
    $table->addCell()->addText("2",$font, $centered);
    $table->addCell()->addText("3",$font, $centered);
    $table->addCell()->addText("4",$font, $centered);
    $table->addCell()->addText("5",$font, $centered);
    $table->addCell()->addText("6",$font, $centered);
    $table->addCell()->addText("7",$font, $centered);
    $table->addCell()->addText("8",$font, $centered);
    $table->addCell()->addText("9",$font, $centered);
    $table->addCell()->addText("10",$font, $centered);
    $table->addCell()->addText("11",$font, $centered);
    $table->addCell()->addText("12",$font, $centered);
    $table->addCell()->addText("13",$font, $centered);
    $table->addCell()->addText("14",$font, $centered);
    $table->addCell()->addText("15",$font, $centered);
    $table->addCell()->addText("16",$font, $centered);
    $table->addCell()->addText("17",$font, $centered);
    $table->addCell()->addText("18",$font, $centered);

    $grandTotal = 0;
    $contor =1;
    foreach ($aparate as $aparat){
        $variabile = $aparat->getVariabile();
        $contoriIeri = $aparat->getContorByZi(($zi-1));
        $contoriAzi = $aparat->getContorByZi($zi);

        $dIn = $contoriAzi->getIdxInM() - $contoriIeri->getIdxInM();
        $dOut = $contoriAzi->getIdxOutM() - $contoriIeri->getIdxOutM();

        $soldImpuls = $dIn - $dOut;
        $tParticipare = $dIn * $variabile->getPiMec();
        $tPlati = $dOut * $variabile->getPiMec();

        $totalAparat = $tParticipare - $tPlati;

        $grandTotal += $totalAparat;

        $table->addRow();
        $table->addCell()->addText($contor,$font, $centered);
        $table->addCell()->addText($aparat->getSeria(),$font, $centered);
        $table->addCell()->addText($contoriIeri->getIdxInM(),$font, $centered);
        $table->addCell()->addText('0',$font, $centered);
        $table->addCell()->addText($contoriIeri->getIdxOutM(),$font, $centered);
        $table->addCell()->addText($contoriAzi->getIdxInM(),$font, $centered);
        $table->addCell()->addText("0",$font, $centered);
        $table->addCell()->addText($contoriAzi->getIdxOutM(),$font, $centered);
        $table->addCell()->addText($variabile->getFmMec(),$font, $centered);
        $table->addCell()->addText('0',$font, $centered);
        $table->addCell()->addText($variabile->getFmMec(),$font, $centered);
        $table->addCell()->addText($dIn,$font, $centered);
        $table->addCell()->addText('0',$font, $centered);
        $table->addCell()->addText($dOut,$font, $centered);
        $table->addCell()->addText($soldImpuls,$font, $centered);
        $table->addCell()->addText($variabile->getPiMec(),$font, $centered);
        $table->addCell()->addText($tParticipare,$font, $centered);
        $table->addCell()->addText($tPlati,$font, $centered);
        $table->addCell()->addText($totalAparat,$font, $centered);
        $contor++;
    }

    $table->addRow();
    $table->addCell(null,['gridspan' => 18])->addText("INCASARI (VENITURI) LUNARE SLOT MACHINE",[], $centered);
    $table->addCell()->addText($grandTotal,[], $centered);
    $table->addRow();
    $table->addCell(null,['gridspan' => 18])->addText("TOTAL CASTIGURI JACKPOT ACORDATE LUNAR \n (netransferate in pozitia unui cred a unuia dintre mijloacele de joc slot machine)",[], $centered);
    if ($locatie->areJackpot()) {
        $jackpot = $locatie->getJackPotLuna();
        $grandTotal -= $jackpot->getJackPot();
        $table->addCell()->addText($jackpot->getJackPot(),[],$centered);
    }else{
        $table->addCell()->addText("",[],[]);
    }
    $table->addRow();
    $table->addCell(null,['gridspan' => 18])->addText("TOTAL VENITURI LUNARE",[],$centered);
    $table->addCell()->addText($grandTotal,[],$centered);
}

$objWriter = IOFactory::createWriter($word);

$objWriter->save("Zilnice\\Zilnic-{$firma->getDenumire()}-{$appSettings->getCurentDate()}.docx");
header("location:Zilnice\\Zilnic-{$firma->getDenumire()}-{$appSettings->getCurentDate()}.docx");