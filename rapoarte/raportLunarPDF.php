<?php
require_once "../autoloader.php";
require_once "../classes/mpdf/mpdf.php";
$appSettings = new SessionClass();
$page = new PageClass();

$appSettings->checkLogin();

$db = new DataConnection();

$locatie = new LocatiiEntity($db,$appSettings);
$locatie->getLocatie($appSettings->getIdLocatie());

$firma = $locatie->getFirma();

$operator = new OperatorEntity($db,$appSettings);
$operator->getCurrentOperator();
$nrAparate = 12;
$pdf = new mPDF('c', 'A4-L');
$html = "
<html>
<body style='font-size : 12pt; font-weight: 100'>
<style type='text/css' scoped>
    @page{ margin-left: 20px; margin-right: 20px;}
    table {
        font-size : 12px;
        font-weight: 100;
    }
    thead th{
        font-weight: 100;
        }
</style>";
$html .= "<table style='height: 100%;width: 100%; text-align: center' border='1' cellpadding='0' cellspacing='0'>";
$html .= "  <thead>";
$html .= "      <tr>";
$html .= "          <th colspan='15' style='text-align: left; font-weight: 100; font-size: 12px;'>
                    Organizator : {$operator->getDenFirma()}<br/>
                    Domiciliu Fiscal : {$operator->getDomiciliuFiscal()}<br/>
                    Nr. de inregistrare la registrul comertului : {$operator->getRegComert()}<br/>
                    Capital social : {$operator->getCapitalSocial()}<br/>
                    Licenta de organizare activitate slot machine : {$operator->getLicenta()}<br/>
                    Adresa punctului de lucru : {$locatie->getAdresa()}
                    </th>";
$html .= "          <th colspan='4'>
                    {$page->getLuna($appSettings->getLuna())}/{$appSettings->getAn()}
                    </th>";
$html .= "      </tr>";
$html .= "      <tr><th style='text-align: center' colspan='19'><h3>SITUATIA INCASARILOR (VENITURILOR) LUNARE</h3><br/>
                obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)</th></tr>";
$html .= "      <tr>
                    <th>Nr. Crt</th>
                    <th>Seria mijlocului de joc</th>
                    <th colspan='3'>Indexul contoarelor la inceput (Si)</th>
                    <th colspan='3'>Indexul contoarelor la sfarsit (Sf)</th>
                    <th colspan='3'>Factor de multiplicare (F)</th>
                    <th colspan='3'>Diferenta dintre indexurile contoarelor (D) = (Sf - Si x F)</th>
                    <th>Soldul Impulsurilor</th>
                    <th>Pret / impuls</th>
                    <th>Taxa de participare colectata</th>
                    <th>Total plati efectuate de catre jucatori</th>
                    <th>Incasari (venituri) <br/> (lei)</th>
</tr>";
$html .= "<tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>I</th>
            <th>Ej</th>
            <th>Ei</th>
            <th>I</th>
            <th>Ej</th>
            <th>Ei</th>
            <th>I</th>
            <th>Ej</th>
            <th>Ei</th>
            <th>I</th>
            <th>Ej</th>
            <th>Ei</th>
            <th>=11 - 12- 13</th>
            <th>lei</th>
            <th> = 11 * 15</th>
            <th> = 13 * 15</th>
            <th> = 14 * 15 <br/> = 16-17</th>
</tr>";
$html .= "</thead><tbody>";
$html .= "<tr>
           <th>0</th>
           <th>1</th>
           <th>2</th>
           <th>3</th>
           <th>4</th>
           <th>5</th>
           <th>6</th>
           <th>7</th>
           <th>8</th>
           <th>9</th>
           <th>10</th>
           <th>11</th>
           <th>12</th>
           <th>13</th>
           <th>14</th>
           <th>15</th>
           <th>16</th>
           <th>17</th>
           <th>18</th>
</tr>";
$aparate = $locatie->getAparateLocatie();
$contor = 1;
$grandTotal = 0;
/** @var AparatEntity $aparat */
foreach ($aparate as $aparat) {
    $aparat->getContoriZilnici($appSettings->getAn(),$appSettings->getLuna());
    $variabile = $aparat->getVariabile();
    $inceputLuna = $aparat->getInceputLuna();
    $sfarsitLuna = $aparat->getSfarsitLuna();
    $diferentaInAparat = ($sfarsitLuna->getIdxInM() - $inceputLuna->getIdxInM()) * $variabile->getFmMec();
    $diferentaOutAparat = ($sfarsitLuna->getIdxOutM() - $inceputLuna->getIdxOutM()) * $variabile->getFmMec();
    $diferentaTotalaAparat = $diferentaInAparat - $diferentaOutAparat;
    $taxaParticipareAparat = $diferentaInAparat * $variabile->getPiMec();
    $platiEfectuate = $diferentaOutAparat * $variabile->getPiMec();
    $totalAparat = $diferentaTotalaAparat * $variabile->getPiMec();
    $html .= "<tr>
                <td>{$contor}</td>
                <td>{$aparat->getSeria()}</td>
                <td>{$inceputLuna->getidxInM()}</td>
                <td>0</td>
                <td>{$inceputLuna->getIdxOutM()}</td>
                <td>{$sfarsitLuna->getIdxInM()}</td>
                <td>0</td>
                <td>{$sfarsitLuna->getIdxOutM()}</td>
                <td>{$variabile->getFmMec()}</td>
                <td>0</td>
                <td>{$variabile->getFmMec()}</td>
                <td>{$diferentaInAparat}</td>
                <td>0</td>
                <td>{$diferentaOutAparat}</td>
                <td>{$diferentaTotalaAparat}</td>
                <td>{$variabile->getPiMec()}</td>
                <td>{$taxaParticipareAparat}</td>
                <td>{$platiEfectuate}</td>
                <td>{$totalAparat}</td>
              </tr>";
    unset($inceputLuna);
    unset($sfarsitLuna);
    $grandTotal += $totalAparat;
    $contor++;
}
$nrAparateLaLocatie = count($aparate);
while($nrAparateLaLocatie < $nrAparate){
    $numerotare = $nrAparateLaLocatie +1;
    $html.= "
    <tr>
        <td>{$numerotare}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    ";
    $nrAparateLaLocatie++;
}
$html .= "<tr>
        <td colspan='18'><h3>INCASARI (VENITURI) LUNARE SLOT MACHINE</h3></td>
        <td>{$grandTotal}</td>
    </tr>";
$html .= "<tr>
        <td colspan='18'>TOTAL CASTIGURI JACKPOT ACORDATE LUNAR <br/>
        (netransferate in pozitia unui credit a unuia dintre mijloacele de joc slot machine)</td>
        <td>";
if($locatie->areJackpot()){
    $jackpot = $locatie->getJackPotLuna();
    $grandTotal -= $jackpot->getJackPot();
    $html.= "{$jackpot->getJackPot()}";
}
$html .="</td>
</tr>";
$html .= "<tr>
            <td colspan='18'><h3>TOTAL VENITURI LUNARE</h3></td>
            <td>{$grandTotal}</td>
          </tr>";
$html .= "  </tbody>";
$html .= "</table>
</body></html>";
$pdf->writeHTML($html);
$pdf->output("Lunare\\Lunar{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}.pdf");

header("location:Lunare\\Lunar{$firma->getDenumire()}-{$appSettings->getLuna()}-{$appSettings->getAn()}.pdf");