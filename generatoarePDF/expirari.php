<?php

require_once "../autoloader.php";
require_once "../classes/mpdf/mpdf.php";

$session = new SessionClass();

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$session->exchangeArray($_SESSION);
/*
 * Helper Class
 */
$page = new PageClass();

/**
 * Verifica daca cererea este corecta
 */
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);

/**
 * Pregateste datele pentru prelucrare
 */
$post = $db->sanitizePost($_POST);

/**
 * Preia expirarile pe luna respectiva
 */
$expirari = $db->getAvertizariByLuna($post['tip'], $post['an'], $post['luna'], $post['operator']);
$nrExpirari = count($expirari);
$tip = ($post['tip'] == "dtExpMetrologie") ? "metrologii" : "autorizari";
$operator = $post['operator']==1 ? "Ampera" : "Redlong";
/**
 * generarea tabelului
 */

$pdf = new mPDF();

$html ="";
$html .= "<div class=\"pull-right col-md-6\"><h4 class=\"inline\">In luna {$page->getLuna($post['luna'])}  expira {$nrExpirari}
    {$tip} pe firma {$operator}</h4></div>
<table class=\"table table-responsive table-bordered\" border='1'>
    <thead>
    <tr>
        <th>Nr. Crt</th>
        <th>Responsabil</th>
        <th>Locatie</th>
        <th>Serie</th>
        <th>Tip</th>
        <th>Data Expirare Metrologie</th>
        <th>Data Expirare Autorizatie</th>
    </tr>
    </thead>
    <tbody>";
    $i = 1;
    $responsabil = '';
    $locatie = '';
    foreach ($expirari as $expirare) {

        $dataMetrologie = new DateTime($expirare->dtExpMetrologie);
        $dataAutorizatie = new DateTime($expirare->dtExpAutorizatie);
        $html.="<tr>
            <td>{$i}</td>
            <td>";
            if ($expirare->nick != $responsabil) {
                   $html .= "$expirare->nick";
                    $responsabil = $expirare->nick;
                }
            $html.="
                </td>
            <td>";
                if ($expirare->denumire != $locatie) {
                   $html.=" $expirare->denumire";
                    $locatie = $expirare->denumire;
                }
             $html.= "</td>
            <td>{$expirare->seria}</td>
            <td>{$expirare->tip}</td>
            <td>{$dataMetrologie->format("d M Y")}</td>
            <td>{$dataAutorizatie->format("d M Y")}</td>
        </tr>";
        $i++;
    }
    $html .="</tbody>
</table>";

$pdf->writeHTML($html);
$pdf->output("{$tip}-{$post['luna']}.pdf");

header("location:{$tip}-{$post['luna']}.pdf");
?>