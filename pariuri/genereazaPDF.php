<?php


require_once "../autoloader.php";
require_once "../classes/mpdf/mpdf.php";

$session = new SessionClass();

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);

$page = new PageClass();

$page->checkLogin($session->getUsername(),$session->getOperator());

$pdf = new mPDF();


$post = $db->sanitizePost($_POST);

$personalMapper = new PersonalMapper($db);
$colaborator = $personalMapper->getPersonal($post['responsabil']);


$html ="<h2 style='text-align: center'>Locatiile colaboratorului {$colaborator->getNick()}</h2>";;

$html .="<table border='1' style='text-align: center;' cellpadding='0' cellspacing='0' width='800px;'>
                <tr>
                    <th>Nr Crt.</th>
                    <th>Denumire</th>
                    <th>Analitic</th>
                    <th>Adresa</th>
                </tr>";

$locatiiPariuri = $colaborator->getAgentiiPariuri($db);
$i=1;
/** @var AgentiiEntity $locatie */
foreach($locatiiPariuri as $locatie){
    $html .="<tr>
            <td>{$i}</td>
            <td>{$locatie->getDenumire()}</td>
            <td>{$locatie->getAnalitic()}</td>
            <td>{$locatie->getAdresa()}</td>
        </tr>";
    $i++;

}

$html .= "</table>";

$pdf->writeHTML($html);

$pdf->output("pdf\\Locatii{$colaborator->getNick()}-{$session->getLuna()}-{$session->getAn()}.pdf","F");


header("location:pdf\\Locatii{$colaborator->getNick()}-{$session->getLuna()}-{$session->getAn()}.pdf");