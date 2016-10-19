<?php

// ----------------------------------------------------------------------------------------------------
// - Display Errors
// ----------------------------------------------------------------------------------------------------
ini_set('display_errors', 'On');
ini_set('html_errors', 0);

// ----------------------------------------------------------------------------------------------------
// - Error Reporting
// ----------------------------------------------------------------------------------------------------
error_reporting(0);

// ----------------------------------------------------------------------------------------------------
// - Shutdown Handler
// ----------------------------------------------------------------------------------------------------
function ShutdownHandler()
{
    if(@is_array($error = @error_get_last()))
    {
        return(@call_user_func_array('ErrorHandler', $error));
    };

    return(TRUE);
};

register_shutdown_function('ShutdownHandler');

// ----------------------------------------------------------------------------------------------------
// - Error Handler
// ----------------------------------------------------------------------------------------------------
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );

    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };

    return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
};

$old_error_handler = set_error_handler("ErrorHandler");

// other php code

?>

<?php
include 'includes/_db.inc.php';
require_once('classes/SessionClass.php');
require_once("classes/DataConnection.php");
use PhpOffice\PhpWord\PhpWord;
require_once "classes/Mappers/OperatoriMapper.php";
require_once "classes/Entityes/OperatorEntity.php";
require_once 'includes/dbFull.php';
require_once 'classes/PageClass.php';
require_once 'classes/vendor/autoload.php';

$session = new SessionClass();
$page = new PageClass();
$db = new DataConnection();
$dbfull = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$paragraphStyle = [
    'lineHeight' => 1,
    'bold' => true
];
$get = $db->sanitizePost($_GET);
$word = new PhpWord();

$operatorMapper = new OperatoriMapper($db,$session);
$operator = $operatorMapper->getOperator($get['id']);

/**
 * Basic config
 */
$word->setDefaultFontName("Times New Roman");
$word->setDefaultFontSize(12);

$section = $word->addSection();
$section->getStyle()->setPageNumberingStart(1);

$section->addText(htmlspecialchars($operator->getDenFirma()), $paragraphStyle);
$section->addText(htmlspecialchars(""), $paragraphStyle);
$section->addText(htmlspecialchars(""), $paragraphStyle);
$section->addText(htmlspecialchars(""), $paragraphStyle);
$section->addText(htmlspecialchars(""), $paragraphStyle);

$section->addText(htmlspecialchars(''));

$section->addText(htmlspecialchars('Către,'), $paragraphStyle);

$paragraphStyle = [
    'lineHeight' => 1,
    'bold' => true,
];
$fontStyle = [
    'align' => 'center'
];


$section->addText(htmlspecialchars('MINISTERUL FINANȚELOR PUBLICE,'), $paragraphStyle, $fontStyle);
$section->addText(htmlspecialchars('COMISIA DE AUTORIZARE A JOCURILOR DE NOROC'), $paragraphStyle, $fontStyle);

$textrun = $section->createTextRun();

$aparate = $dbfull->getAvertizariByLuna('dtExpAutorizatie', $get['an'], $get['luna'], $get['id']);
$nrAparate = count($aparate);


$textrun->addText(htmlspecialchars('    Prin prezenta, vă solicităm să aprobați reautorizarea unui număr de '));
$textrun->addText(htmlspecialchars($nrAparate . ' ( )'), ['bold' => true]);

$textrun->addText(htmlspecialchars(' mașini electronice cu câștiguri, conform locurilor de exploatare a seriilor de identificare specificate în continuare :'));

$locatii = [];
foreach ($aparate as $aparat) {
    $locatii[$aparat->regiune][$aparat->adresa][] = $aparat;
}

asort($locatii);
$i = 1;
$j = 1;
foreach ($locatii as $regiune) {
    foreach ($regiune as $key => $value) {
        $section->addText(htmlspecialchars('    ' . $i . '. ' . $key), ['bold' => true]);
        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize' => 6,
            'width' => '100%',
        );
        $word->addTableStyle('aparateTable', $tableStyle);
        $table = $section->addTable('aparateTable');
        $table->addRow();
        $table->addCell(1000)->addText('Nr. Crt.', [], ['align' => 'center', 'spaceAfter' => 0]);
        $table->addCell(2000)->addText('SERIE APARAT', [], ['align' => 'center', 'spaceAfter' => 0]);
        $table->addCell(1500)->addText('AN DE FABRICATIE', [], ['align' => 'center', 'spaceAfter' => 0]);
        $table->addCell(3000)->addText('PRODUCATOR', [], ['align' => 'center', 'spaceAfter' => 0]);
        $table->addCell(3500)->addText("TIP JOC",[],['align' => 'center', 'spaceAfter' => 0]);
        $table->addCell(1000)->addText("%",[],['align' => 'center', 'spaceAfter' => 0]);
        foreach ($value as $aparat) {
            $table->addRow();
            $table->addCell()->addText($j, [], ['align' => 'center', 'spaceAfter' => 0]);
            $table->addCell()->addText($aparat->seria, [], ['align' => 'center', 'spaceAfter' => 0]);
            $table->addCell()->addText(" ", [], ['align' => 'center', 'spaceAfter' => 0]);
            $table->addCell()->addText(" ", [], ['align' => 'center', 'spaceAfter' => 0]);
            $table->addCell()->addText("CLUB MASTER", [], ['align' => 'center', 'spaceAfter' => 0]);
            $table->addCell()->addText(" ", [], ['align' => 'center', 'spaceAfter' => 0]);
            $j++;
        }
        $i++;
    }
}

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word);
$objWriter->save('documente/Autorizari-'.$operator->getIdoperator().'-'.date('m').'-'.date('Y').'.docx');
header('location:documente/Autorizari-'.$operator->getIdoperator().'-'.date('m').'-'.date('Y').'.docx');
