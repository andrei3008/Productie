<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/mailer/PHPMailerAutoload.php');
require_once('classes/mailer/class.phpmailer.php');
require_once('classes/PageClass.php');
require_once('classes/FileClass.php');
$appSettings = $session = new SessionClass();
$file = new FileClass('text/posibilemutari.txt');
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
if (isset($_POST['submit'])) {
    $post = $db->sanitizePost($_POST);
    $fisier = fopen('text/locatii.txt', 'w');
    foreach ($post as $key => $value) {
        fwrite($fisier, $key . ' ' . $value . "\n");
    }
}
if (isset($_GET['linie'])) {
    $get = $db->sanitizePost($_GET);
    $file->deleteLine($get['linie']);
    header('Location: ' . $_SERVER['PHP_SELF']);
}
$mailer = new PHPMailer();
if (isset($_POST['submitMutare'])) {
    $post = $db->sanitizePost($_POST);
    $line = '';
    foreach ($post as $key => $value) {
        $line .= $value . ';';
    }
    $line .= "\n";
    $file->writeToFile($line);
}
$fisierCitire = fopen('text/locatii.txt', 'r');
$locatiiNoi = array();
$i = 0;
$totalAmpera = 0;
$totalRedlong = 0;
$aparateDepozit = $db->getAparateDepozitByResponsabili();
foreach ($aparateDepozit as $aparatN) {
    $letter = substr($aparatN->denumireLocActual, -1, 1);
    if ($letter == "A") {
        $totalAmpera += 1;
    } else {
        $totalRedlong += 1;
    }
}
while (!feof($fisierCitire) or $i < count($aparateDepozit)) {
    $randNou = fgets($fisierCitire);
    if ($randNou) {
        $parti = explode(' ', $randNou);
        $bucati = explode('_', $parti[0]);
        if (isset($bucati[1])) {
            $locatiiNoi[$bucati[1]] = $parti[1];
        }
    }
    $i++;
}
$page = new PageClass();
?>
<!DOCTYPE>
<html>
<head>
    <title>Aparate per Depozit</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<?php require_once('includes/menu.php'); ?>
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">Tabel Depozite</div>
        <div class="panel-body table-responsive">
            <form method="POST">
                <table class="table table-bordered table-condensed" id="table-small">
                    <thead>
                    <tr>
                        <td colspan="6">Total Aparate Ampera :<strong><?php echo $totalAmpera ?></strong></td>
                        <td colspan="6">Total Aparate Redlong :<strong><?php echo $totalRedlong ?></strong></td>
                    </tr>
                    <tr>
                        <th>Nr.</th>
                        <th>Nr.<br/> crt</th>
                        <th>Serie</th>
                        <th>Loc Vechi</th>
                        <th>Loc nou</th>
                        <th>Contor IN</th>
                        <th>Contor OUT</th>
                        <th>Data Index</th>
                        <th>Zile</th>
                        <th>Firma</th>
                        <th class="no-print">Observatii</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $aparateDepozit = $db->getAparateDepozitByResponsabili();
                    $nrAparatePerLocatie = [];
                    $locatieInitiala = '';
                    foreach ($aparateDepozit as $aparat) {
                        if ($locatieInitiala != $aparat->denumireLocVechi) {
                            $nrAparatePerLocatie[$aparat->idlocatie] = 1;
                            $locatieInitiala = $aparat->denumireLocVechi;
                        } else {
                            $nrAparatePerLocatie[$aparat->idlocatie] += 1;
                        }

                    }
                    $nick = '';
                    $denumireLocVechi = '';
                    $denumireLocVechi2 = '';
                    $i = 1;
                    $u = 1;
                    foreach ($aparateDepozit as $aparat) {
                        if ($aparat->denumireLocVechi != $denumireLocVechi2) {
                            ?>
                            <tr>
                                <td colspan="11" class="delimiter"></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr class="<?php echo ($aparat->operator == 2) ? "red-bg" : "blue-bg" ?>">
                            <?php
                            if ($aparat->denumireLocVechi != $denumireLocVechi2) {
                                $denumireLocVechi2 = $aparat->denumireLocVechi;
                                ?>
                                <td rowspan="<?php echo $nrAparatePerLocatie[$aparat->idlocatie]; ?>" style="vertical-align: middle;"><?php echo $u;$u++; ?></td>
                                <?php
                            }
                            ?>
                            <td>
                                <?php echo $i; ?>
                            </td>
                            <td><?php echo $aparat->seria; ?></td>

                            <td><?php if ($aparat->denumireLocVechi != $denumireLocVechi) {
                                    echo '<strong>' . $aparat->nick . '</strong>';
                                }
                                if ($aparat->denumireLocVechi != $denumireLocVechi) {
                                    $denumireLocVechi = $aparat->denumireLocVechi;
                                    echo ' ' . $denumireLocVechi;
                                } ?></td>
                            <td data-title=""><input type="text" name="aparat_<?php echo $aparat->idAparat ?>"
                                                     placeholder="Locatie noua"
                                                     value="<?php echo (array_key_exists($aparat->idAparat, $locatiiNoi)) ? $locatiiNoi[$aparat->idAparat] : ''; ?>"/>
                            </td>
                            <td><?php echo $page->verifyIndexLength($aparat->lastIdxInM); ?></td>
                            <td><?php echo $page->verifyIndexLength($aparat->lastIdxOutM); ?></td>
                            <td><?php echo $page->afiseazaDatA($aparat->dtLastM); ?></td>
                            <td>
                                <?php
                                $datetime1 = date_create(date('Y-m-d'));
                                $datetime2 = date_create($aparat->dtActivare);
                                $interval = date_diff($datetime2, $datetime1);
                                echo $interval->format('%R%a zile');
                                ?>
                            </td>
                            <td>
                                <?php
                                $letter = substr($aparat->denumireLocActual, -1, 1);
                                if ($letter == "A") {
                                    echo "A";
                                } else {
                                    echo "R";
                                }
                                ?>
                            </td>
                            <td class="no-print"><a
                                    href="ftp://acte:acte77@rodiz.ro/metrologii/curente/<?php echo $aparat->seria ?>.pdf"
                                    target="_blank" class="btn btn-sm btn-primary">Metrologie</a></td>
                        </tr>
                        <?php

                        $i++;
                    }
                    ?>
                    <tr>
                        <td colspan="11" class="delimiter"></td>
                    </tr>
                    <tr>
                        <td colspan="3"><h3>Total : <strong><?php echo $i - 1; ?> bucati</strong></h3></td>
                        <td colspan="8"><input type="submit" name="submit" value="Salveaza Potentiale Locatii"
                                               class="btn btn-md btn-primary" style="margin-top : 20px;"/></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <button class="btn btn-sm btn-primary" id="showPanelMutari">Adauga posibila mutare</button>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#showPanelMutari').click(function (event) {
                        event.preventDefault();
                        $('#panelMutari').toggle();
                    });
                });
            </script>
        </div>
        <div class="row">
            <div class="panel panel-primary" id="panelMutari" style="display: none;">
                <div class="panel-heading">Adauga Posibila Mutare</div>
                <div class="panel-body">
                    <form method="POST">
                        <input type="hidden" name="nrMutare" value="<?php echo $file->getNumberOfLastRow() ?>"/>
                        <fieldset>
                            <label for="serie">Serie</label>
                            <input type="text" name="serie" class="form-control"/>
                        </fieldset>
                        <fieldset>
                            <label for="locatieVeche">Locatie Veche</label>
                            <input type="text" name="locatie-veche" class="form-control"/>
                        </fieldset>
                        <fieldset>
                            <label for="Minister">Minister</label>
                            <input type="text" name="minister" class="form-control"/>
                        </fieldset>
                        <fieldset>
                            <label for="locatie-noua">Locatie noua</label>
                            <input type="text" name="locatie-noua" class="form-control"/>
                        </fieldset>
                        <fieldset>
                            <button name="submitMutare" type="submit" value="1">Adauga</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">Posibile mutari</div>
                <div class="panel-body">
                    <table class="table table-responsive table-bordered">
                        <thead>
                        <tr>
                            <th>Nr. Crt.</th>
                            <th>Serie</th>
                            <th>Locatie veche</th>
                            <th>Minister</th>
                            <th>Locatie Noua</th>
                            <th>Actiune</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $text = $file->getText();
                        $i = 1;
                        foreach ($text as $line) {
                            if ($line != '') {
                                $coloane = explode(';', $line);
                                ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td><?php echo $coloane[1]; ?></td>
                                    <td><?php echo $coloane[2]; ?></td>
                                    <td><?php echo $coloane[3]; ?></td>
                                    <td><?php echo $coloane[4]; ?></td>
                                    <td><a href="?linie=<?php echo $coloane[0]; ?>" class="btn btn-sm btn-primary">Sterge</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
