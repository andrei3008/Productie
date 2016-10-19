<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\ListItem;

require_once "../autoloader.php";
require_once "../classes/vendor/autoload.php";
$db = new DataConnection();
$appSettings = new SessionClass();


$appSettings->setOperator($_POST['operator']);

$page = new PageClass();

$locatiiMapper = new LocatiiMaper($db, $appSettings);

$regiune = $_POST['regiune'];

if ($regiune == "Dolj" OR $regiune == "Mehedinti" OR $regiune == "Olt" OR $regiune == "Gorj") {
    $operator = new OperatorEntity($db, $appSettings);
    $word = new PhpWord();
    $operator->getOperator($_POST['operator']);

    $section = $word->addSection([
        "orientation" => "landscape",
        "marginTop" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
        "marginLeft" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
        "marginRight" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2),
        "marginBottom" => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.2)
    ]);

    $tableStyle = [
        "borderColor" => "000000",
        "borderSize" => 6
    ];

    $centered = [
        "align" => "center"
    ];

    $header = "Societatea : {$operator->getDenFirma()}<w:br/>Adresa: {$operator->getDomiciliuFiscal()}    Date de identificare la registrul comertului : {$operator->getRegComert()}; CUI : {$operator->getCui()}<w:br/>Telefon : ....................... Fax : ................................ <w:br/>
        Persoana de contact : Nume si prenume .......................................  Telefon ........................................";

    $section->addText($header);

    $catre = "<w:br/>Către : Direcția regională de Metrologie Legală Craiova / Serviciul Județean de Metrologie Legală ..........<w:br/>";

    $section->addText($catre, ["size" => 13], ["align" => "center"]);

    $word->addTableStyle("tabelAparate", $tableStyle);
    $table = $section->addTable("tabelAparate");


    $table->addRow();
    $table->addCell(300)->addText("Nr. Crt.", [], $centered);
    $table->addCell(5000)->addText("Tip Mijloc de joc (MDJ) /<w:br/> Aprobare Tip", [], $centered);
    $table->addCell(2000)->addText("Serie", [], $centered);
    $table->addCell(2000)->addText("Tip Verificare<w:br/>(I;P;R)", [], $centered);
    $table->addCell(5000)->addText("Document de proveniență<w:br/>(tip; serie; nr; data eliberării)", [], $centered);
    $table->addCell(3000)->addText("Loc de funcționare <w:br/>MDJ", [], $centered);

    $i = 1;
    $locatii = $locatiiMapper->getLocatiiByRegiune($regiune);
    /** @var LocatiiEntity $locatie */
    foreach ($locatii as $locatie) {
        $aparate = $locatie->getAparateLocatie();

        /** @var AparatEntity $aparat $aparat */
        foreach ($aparate as $aparat) {
            $aparat->getAvertizari();
            if ($aparat->expiraLunaAsta()) {
                $table->addRow();
                $table->addCell(300)->addText($i, [], $centered);
                $table->addCell(5000)->addText("VIDEO-GAME<w:br/>", [], $centered);
                $table->addCell(2000)->addText($aparat->getSeria(), [], $centered);
                $table->addCell(2000)->addText("P", [], $centered);
                $table->addCell(2000)->addText("CONTRACT DE INCHIRIERE NR <w:br/>", [], $centered);
                $table->addCell(5000)->addText($locatie->getAdresa(), [], $centered);
                $i++;
            }
        }
    }

    $footer = $section->createFooter();
    $footer->addPreserveText("Pagina {PAGE} din {NUMPAGES}.                                                                                                                                                                 ____________________________Semnătură și ștampilă organizator", array("align" => "center"));


    $objWriter = IOFactory::createWriter($word);
    $objWriter->save("Zonal\\{$regiune}-{$operator->getDenBaza()}.docx");
    header("location:Zonal\\{$regiune}-{$operator->getDenBaza()}.docx");

} else {


    $word = new PhpWord();

    $operator = new OperatorEntity($db, $appSettings);
    $operator->getOperator($_POST['operator']);

    $centered = [
        "align" => "center"
    ];

    $header = "       Societatea : {$operator->getDenFirma()}
<w:br/>       Adresa : {$operator->getDomiciliuFiscal()}
<w:br/>       Fax :  ................................................................................................................................................<w:br/>       Persoana de contact si telefon : .......................................................................................................";


    $section = $word->addSection();
    $section->addText($header);

    $catre = "<w:br/>               Către ......................................................................................                ";
    $section->addText($catre, ["size" => 13], $centered);

    $scrisSubCatre = "(Biroul Român de Metrologie Legală - Serviciul Mijloace de joc sau Directia Regioanală de Metrologie Legală .......)<w:br/>";

    $section->addText($scrisSubCatre, ["size" => "8"], $centered);

    $vaRugam = "  Vă rugăm să efectuați o verificare tehnică a urmatoarelor mijloace de joc :";

    $section->addText($vaRugam);

// Add listitem elements
    $listStyle = array('listType' => ListItem::TYPE_NUMBER);

    $nrMijloaceDeJoc = 0;


    foreach ($_POST['luna'] as $lunaCurenta) {
        $appSettings->setLuna($lunaCurenta);
        $locatii = $locatiiMapper->getLocatiiByRegiune($regiune);
        /** @var LocatiiEntity $locatie */
        foreach ($locatii as $locatie) {
            $aparate = $locatie->getAparateLocatie();

            /** @var AparatEntity $aparat */
            foreach ($aparate as $aparat) {
                $aparat->getAvertizari();

                if ($aparat->expiraLunaAsta()) {
                    $info = "Tip Mijloc de joc VIDEO-GAME                                                        Aprobare de tip nr. ............<w:br/>
Serie : {$aparat->getSeria()}                                                                          Tipul Verificării : PERIODICA<w:br/>
Documente de proveniență CONTRACT INCHIRIERE NR ......................................................<w:br/>...................................................................................................................................................<w:br/>Locul verificării tehnice : {$locatie->getAdresa()}<w:br/>Locul de funcționare al mijlocului de joc : {$locatie->getAdresa()}";
                    $section->addListItem($info, 0, null, $listStyle);
                    $nrMijloaceDeJoc++;
                }
            }

        }
    }
    $section->addTextBreak(2);

    $textFinal = "Prezenta ține loc de comandă fermă.<w:br/>Ne asumăm responsabilitatea privind corectitudinea documentelor din dosarul atașat prezentei cereri.";
    $section->addText($textFinal);

    $data = date('d-M-Y');

    $finalDocument = "Data : {$data}                                                                    Nr. mijloace de joc : {$nrMijloaceDeJoc}";
    $section->addText($finalDocument);


    $footer = $section->createFooter();
    $footer->addPreserveText("Pagina {PAGE} din {NUMPAGES}.                                             ____________________________Semnătură și ștampilă organizator", array("align" => "center"));
    $objWriter = IOFactory::createWriter($word);
    $objWriter->save("Zonal\\{$regiune}-{$operator->getDenBaza()}.docx");
    header("location:Zonal\\{$regiune}-{$operator->getDenBaza()}.docx");
}
