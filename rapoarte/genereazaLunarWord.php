<?php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

require_once "../autoloader.php";
require_once "../classes/vendor/autoload.php";

$appSettings = new SessionClass();
$page = new PageClass();

$word = new PhpWord();
$db = new DataConnection();
$locatie = new LocatiiEntity($db, $appSettings);
$locatie->getLocatie($appSettings->getIdLocatie());
$firma = $locatie->getFirma();
$operator = new OperatorEntity($db, $appSettings);
$operator->getCurrentOperator();
$word->setDefaultFontName("Times New Roman");
$section = $word->addSection(["orientation" => "landscape", 'width' => '100%','margin-top' => 50]);

$tableStyle = array(
    'borderColor' => '000000',
    'borderSize' => 6,
);

$word->addTableStyle('aparateTable', $tableStyle);
$table = $section->addTable('aparateTable');
$table->addRow();

$headerInfo = "Organizatie : {$operator->getDenFirma()}<w:br/>
Domiciliu Fiscal : {$operator->getDomiciliuFiscal()} <w:br/>
Nr. de inregistrare la registrul comertului : {$operator->getRegComert()} <w:br/>
Capital Social : {$operator->getCapitalSocial()} <w:br/>
Licenta de organizare activitate slot machine : {$operator->getLicenta()} <w:br/>
Adresa punctului de lucru : {$locatie->getAdresa()}
";
$data = "<w:br/><w:br/><w:br/>{$page->getLuna($appSettings->getLuna())} / {$appSettings->getAn()}";

$table->addCell(10000,['gridspan' => 15])->addText($headerInfo, ['align' => 'left', 'spaceAfter' => 0]);
$table->addCell(4838,['gridspan' => 4])->addText($data, ['align' => 'left', 'spaceAfter' => 0]);

$table->addRow();
$table->addCell(14838,["gridspan" => 19])->addText("SITUATIA INCASARILOR (VENITURILOR) LUNARE <w:br/> obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)",[], array("align" => 'center'));
$rowStyle = [
    'align' => 'center'
];
$table->addRow();
$table->addCell(300)->addText("Nr. Crt.",[], $rowStyle);
$table->addCell(1700)->addText("Seria mijlocului de joc",[], $rowStyle);
$table->addCell(1700,['gridspan' => 3])->addText("Indexul contoarelor la inceput (Si)",[], $rowStyle);
$table->addCell(1700,['gridspan' => 3])->addText("Indexul contoarelor la sfarsit (Sf)",[], $rowStyle);
$table->addCell(1700,['gridspan' => 3])->addText("Factor de multiplicare (F)",[], $rowStyle);
$table->addCell(1700,['gridspan' => 3])->addText("Diferenta dintre indexurile contoarelor (D) = (Sf - Si x F)",[], $rowStyle);
$table->addCell(1000)->addText("Soldul impulsurilor",[], $rowStyle);
$table->addCell(500)->addText("Pret  / Impuls",[], $rowStyle);
$table->addCell(1200)->addText("Taxa de participare colectata",[], $rowStyle);
$table->addCell(1200)->addText("Total plati efectuate de catre jucatori",[], $rowStyle);
$table->addCell(1238)->addText("Incasari (venituri) (lei)",[], $rowStyle);

$table->addRow();
$table->addCell()->addText('',[], $rowStyle);
$table->addCell()->addText('',[], $rowStyle);
$table->addCell(700)->addText('I',[], $rowStyle);
$table->addCell(300)->addText("Ej",[], $rowStyle);
$table->addCell(700)->addText('Ei',[], $rowStyle);
$table->addCell(700)->addText('I',[], $rowStyle);
$table->addCell(300)->addText('Ej',[], $rowStyle);
$table->addCell(700)->addText('Ei',[], $rowStyle);
$table->addCell(700)->addText('I', [],$rowStyle);
$table->addCell(300)->addText('Ej',[], $rowStyle);
$table->addCell(700)->addText('Ei',[], $rowStyle);
$table->addCell(700)->addText('I',[], $rowStyle);
$table->addCell(300)->addText('Ej',[], $rowStyle);
$table->addCell(700)->addText('Ei',[], $rowStyle);
$table->addCell()->addText('=11 - 12 -13', [],$rowStyle);
$table->addCell()->addText('lei', [],$rowStyle);
$table->addCell()->addText("= 11 * 15",[], $rowStyle);
$table->addCell()->addText("= 13 * 15",[], $rowStyle);
$table->addCell()->addText("= 14 * 15 = 16 - 17",[], $rowStyle);

$table->addRow();
$table->addCell()->addText("0",[], $rowStyle);
$table->addCell()->addText("1",[], $rowStyle);
$table->addCell()->addText("2",[], $rowStyle);
$table->addCell()->addText("3",[], $rowStyle);
$table->addCell()->addText("4",[], $rowStyle);
$table->addCell()->addText("5",[], $rowStyle);
$table->addCell()->addText("6",[], $rowStyle);
$table->addCell()->addText("7",[], $rowStyle);
$table->addCell()->addText("8",[], $rowStyle);
$table->addCell()->addText("9",[], $rowStyle);
$table->addCell()->addText("10",[], $rowStyle);
$table->addCell()->addText("11",[], $rowStyle);
$table->addCell()->addText("12",[], $rowStyle);
$table->addCell()->addText("13",[],$rowStyle);
$table->addCell()->addText("14",[], $rowStyle);
$table->addCell()->addText("15",[], $rowStyle);
$table->addCell()->addText("16",[], $rowStyle);
$table->addCell()->addText("17",[], $rowStyle);
$table->addCell()->addText("18",[], $rowStyle);

$aparate = $locatie->getAparateLocatie();
$contor = 1;
$grandTotal = 0;
/** @var AparatEntity $aparat */
foreach ($aparate as $aparat) {
    $aparat->getContoriZilnici($appSettings->getAn(), $appSettings->getLuna());
    $variabile = $aparat->getVariabile();
    $inceputLuna = $aparat->getInceputLuna();
    $sfarsitLuna = $aparat->getSfarsitLuna();
    $diferentaInAparat = ($sfarsitLuna->getIdxInM() - $inceputLuna->getIdxInM()) * $variabile->getFmMec();
    $diferentaOutAparat = ($sfarsitLuna->getIdxOutM() - $inceputLuna->getIdxOutM()) * $variabile->getFmMec();
    $diferentaTotalaAparat = $diferentaInAparat - $diferentaOutAparat;
    $taxaParticipareAparat = $diferentaInAparat * $variabile->getPiMec();
    $platiEfectuate = $diferentaOutAparat * $variabile->getPiMec();
    $totalAparat = $diferentaTotalaAparat * $variabile->getPiMec();
    $grandTotal += $totalAparat;
    $table->addRow();
    $table->addCell()->addText($contor,[], $rowStyle);
    $table->addCell()->addText($aparat->getSeria(),[], $rowStyle);
    $table->addCell()->addText($inceputLuna->getIdxInM(),[], $rowStyle);
    $table->addCell()->addText('0',[], $rowStyle);
    $table->addCell()->addText($inceputLuna->getIdxOutM(),[], $rowStyle);
    $table->addCell()->addText($sfarsitLuna->getIdxInM(),[], $rowStyle);
    $table->addCell()->addText("0",[], $rowStyle);
    $table->addCell()->addText($sfarsitLuna->getIdxOutM(),[], $rowStyle);
    $table->addCell()->addText($variabile->getFmMec(),[], $rowStyle);
    $table->addCell()->addText('0',[], $rowStyle);
    $table->addCell()->addText($variabile->getFmMec(),[], $rowStyle);
    $table->addCell()->addText($diferentaInAparat,[], $rowStyle);
    $table->addCell()->addText('0',[], $rowStyle);
    $table->addCell()->addText($diferentaOutAparat,[], $rowStyle);
    $table->addCell()->addText($diferentaTotalaAparat,[], $rowStyle);
    $table->addCell()->addText($variabile->getPiMec(),[], $rowStyle);
    $table->addCell()->addText($taxaParticipareAparat,[], $rowStyle);
    $table->addCell()->addText($platiEfectuate,[], $rowStyle);
    $table->addCell()->addText($totalAparat,[], $rowStyle);
}
$nrAparate = 5;
$nrAparateLaLocatie = count($aparate);
while ($nrAparateLaLocatie < $nrAparate) {
    $numerotare = $nrAparateLaLocatie + 1;
    $table->addRow();
    $table->addCell()->addText($numerotare,[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $table->addCell()->addText("",[], $rowStyle);
    $nrAparateLaLocatie++;
}

$table->addRow();
$table->addCell(null,['gridspan' => 18])->addText("INCASARI (VENITURI) LUNARE SLOT MACHINE",[], $rowStyle);
$table->addCell()->addText($grandTotal,[], $rowStyle);
$table->addRow();
$table->addCell(null,['gridspan' => 18])->addText("TOTAL CASTIGURI JACKPOT ACORDATE LUNAR \n (netransferate in pozitia unui cred a unuia dintre mijloacele de joc slot machine)",[], $rowStyle);
if ($locatie->areJackpot()) {
    $jackpot = $locatie->getJackPotLuna();
    $grandTotal -= $jackpot->getJackPot();
    $table->addCell()->addText($jackpot->getJackPot(),[],$rowStyle);
}else{
    $table->addCell()->addText("",[],[]);
}
$table->addRow();
$table->addCell(null,['gridspan' => 18])->addText("TOTAL VENITURI LUNARE",[],$rowStyle);
$table->addCell()->addText($grandTotal,[],$rowStyle);


$objWriter = IOFactory::createWriter($word);

$objWriter->save("Lunare\\Lunar{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}.docx");
header("location:Lunare\\Lunar{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}.docx");