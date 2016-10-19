<?php
require_once "../autoloader.php";
require_once "../classes/mpdf/mpdf.php";
$appSettings = new SessionClass();
$page = new PageClass();

$appSettings->checkLogin();

$db = new DataConnection(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

$locatie = new LocatiiEntity($db, $appSettings);
$locatie->getLocatie($appSettings->getIdLocatie());

$firma = $locatie->getFirma();

$operator = new OperatorEntity($db, $appSettings);
$operator->getCurrentOperator();

$aparate = $locatie->getAparateLocatie();
/** @var AparatEntity $aparat */
foreach($aparate as $aparat){
    $aparat->getContoriZilnici($appSettings->getAn(),$appSettings->getLuna());
}

$pdf = new mPDF('c', 'A4-L', 10, "Times New Roman", 15, 15, 6);
$html = "";
for ($zi = 1; $zi <= $appSettings->getDaysInCurrentMonth(); $zi++) {
    $nrAparate = count($aparate);
    $html .= "
<style type='text/css'>
    @page : *{ margin : 0.2cm; }
</style>
<table style='height: 100%;width: 100%; text-align: center; margin-top: 20px' border='1' cellpadding='0' cellspacing='0'>
    <tr>
        <td colspan='15' style='text-align: left;'>
            Organizator : {$operator->getDenFirma()}<br/>
            Adresa punctului de lucru : {$locatie->getAdresa()}
        </td>
        <td class='v-align-center text-center' colspan='4'>
            Data
            : {$zi} / {$page->getLuna($appSettings->getLuna())} / {$appSettings->getAn()}
        </td>
    </tr>
    <tr>
        <td colspan='19' class='text-center'>
            <h4>SITUATIA INCASARILOR (VENITURILOR) ZILNICE</h4>
            obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)
        </td>
    </tr>
    <tr class='no-bold text-center'>
        <td>Nr. Crt.</td>
        <td>Seria mijlocului de joc</td>
        <td colspan='3'>Indexul contoarelor la inceput (Si)</td>
        <td colspan='3'>Indexul contoarelor la sfarsit (Sf)</td>
        <td colspan='3'>Factor de multiplicare (F)</td>
        <td colspan='3'>Diferenta dintre indexurile contoarelor (D) = (Sf - Si) x F</td>
        <td>Soldul impulsurilor</td>
        <td>Pretul / impuls</td>
        <td>Taxa de participare colectata (T)</td>
        <td>Total Plati efectuate catre jucatori (P)</td>
        <td>Incasari (venituri) (lei)</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>I</td>
        <td>Ej</td>
        <td>Ei</td>
        <td>I</td>
        <td>Ej</td>
        <td>Ei</td>
        <td>I</td>
        <td>Ej</td>
        <td>Ei</td>
        <td>I</td>
        <td>Ej</td>
        <td>Ei</td>
        <td>= 11 - 12 - 13</td>
        <td>lei</td>
        <td>= 11 * 15</td>
        <td>= 13 * 15</td>
        <td>= 14 * 15 = 16 - 17</td>
    </tr>
    <tr>
        <td>0</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
    </tr> ";

    $grandTotal = 0;

    $i = 1;
    /** @var AparatEntity $aparat */
    foreach ($aparate as $aparat) {
        $dataAzi = new DateTime();
        $dataIeri = $dataAzi->sub(new DateInterval("P1D"));
        $contoriIeri = $aparat->getContorByZi(($zi - 1));
        $contoriAzi = $aparat->getContorByZi($zi);
        $variabile = $aparat->getVariabile();
        $dIn = $contoriAzi->getIdxInM() - $contoriIeri->getIdxInM();
        $dOut = $contoriAzi->getIdxOutM() - $contoriIeri->getIdxOutM();
        $soldImpuls = $dIn - $dOut;
        $tParticipare = $dIn * $variabile->getPiMec();
        $tPlati = $dOut * $variabile->getPiMec();
        $totalAparat = $tParticipare - $tPlati;
        $grandTotal += $totalAparat;
        $html .= "
        <tr>
            <td>{$i}</td>
            <td>{$aparat->getSeria()}</td>
            <td>{$contoriIeri->getIdxInM()}</td>
            <td>0</td>
            <td>{$contoriIeri->getIdxOutM()}</td>
            <td>{$contoriAzi->getIdxInM()}</td>
            <td>0</td>
            <td>{$contoriAzi->getIdxOutM()}</td>
            <td>{$variabile->getFmMec()}</td>
            <td>0</td>
            <td>{$variabile->getFmMec()}</td>
            <td>{$dIn}</td>
            <td>0</td>
            <td>{$dOut}</td>
            <td>{$soldImpuls}</td>
            <td>{$variabile->getPiMec()}</td>
            <td>{$tParticipare}</td>
            <td>{$tPlati}</td>
            <td>{$totalAparat}</td>
        </tr>";
        $i++;
    }
    while($nrAparate <= 5){
        $html.= "<tr>
            <td>{$nrAparate}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>";
        $nrAparate++;
    }

    $html .= "<tr>
        <td colspan='18'>INCASARI (VENITURI) ZILNICE SLOT MACHINE</td>
        <td>{$grandTotal}</td>
    </tr>;";
    if ($locatie->areJackpot()) {
        $jackpot = $locatie->getJackpotZilnic();
        $grandTotal -= $jackpot->getJackPot();
        $html .= "<tr>
            <td colspan='18'>TOTAL CASTIGURI JACKPOT ACORDATE ZILNIC <br/> (netransferate in pozitia credit a unuia
                dintre mijloacele de joc slot machine)
            </td>
            <td>{$jackpot->getJackPot()}</td>
        </tr>";
    }
    $html .= "<tr>
                <td colspan='18'>TOTAL INCASARI (VENITURI) ZILNICE</td>
                <td>{$grandTotal}</td>
            </tr>
        </table>";
}
$pdf->writeHTML($html);
$pdf->output("Zilnice\\Zilnic-{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}-{$appSettings->getZi()}.pdf");

header("location:Zilnice\\Zilnic-{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}-{$appSettings->getZi()}.pdf");