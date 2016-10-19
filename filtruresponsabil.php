<?php
require_once "autoloader.php";
$appSettings = new SessionClass();
$page = new PageClass();
$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
if (!isset($_SESSION['username'])) {
    header('location:index.php');
}
if (isset($_POST['responsabil']) AND $_POST['responsabil'] != 'none') {
    $parti = explode('_', $_POST['responsabil']);
    $idResponsabil = $parti[0];
    $nickResponsabil = $parti[1];
} else {
    $idResponsabil = null;
}
if (!isset($_SESSION['operator']) OR $_SESSION['operator'] != 1 OR $_SESSION['operator'] != 2) {
    $_SESSION['operator'] = 1;
}1
?>
<!DOCTYPE>
<html>
<head>
    <title>Filtru Responsabili</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<?php require_once 'includes/menu.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="text-center">
                        <form method="POST" id="select-responsabil">
                            <fieldset>
                                <label for="responsabil">Alegeti Responsabil</label><br/>
                                <select name="responsabil" class="black">
                                    <option
                                        value=""><?php echo isset($nickResponsabil) ? $nickResponsabil : 'TOTI'; ?></option>
                                    <?php

                                    $objPersoane = $db->getResponsabiliInfo();
                                    if (isset($idResponsabil)) {
                                        ?>
                                        <option value="none">TOTI</option>
                                        <?php
                                    }
                                    foreach ($objPersoane as $persoana) {
                                        ?>
                                        <option
                                            value="<?php echo $persoana->idpers . '_' . $persoana->nick; ?>"><?php echo $persoana->nick ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </fieldset>
                        </form>
                    </div>
                    <div class="left">
                        <strong>Data:</strong> <?php echo $appSettings->getDataDecorata(); ?>
                        <strong>Responsabil: </strong> <?php echo (isset($nickResponsabil)) ? $nickResponsabil : 'TOTI'; ?>
                        <strong>Nr. Locatii: </strong>
                        <?php

                        $objNrLocatii = $db->getNrLocatiiResponsabili($_SESSION['operator'], $idResponsabil);
                        echo $objNrLocatii->nrLocatii;
                        ?>
                        <strong>Numar aparate:</strong>
                        <?php
                        $objNrAparate = $db->getNumarAparateResponsabil($idResponsabil, $_SESSION['operator']);
                        echo $objNrAparate->nrAparate;
                        ?>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <?php
                    $allAparate = $db->getAllAparateResponsabil($idResponsabil, $_SESSION['operator']);
                    foreach ($allAparate as $objAparateResponsabil) {
                        $aparateResponsabil[$objAparateResponsabil->idLocatie][] = $objAparateResponsabil;
                    }

                    $allLocatiiResponsabil = $db->allLocatiiResponsabil($idResponsabil, $_SESSION['operator']);
                    $i = 1;
                    foreach ($allLocatiiResponsabil as $locatieResponsabil) {
                        ?>
                        <div class="panel panel-info">
                            <div
                                class="panel-heading"><?php echo $i . '. <strong>' . $locatieResponsabil->denumire . '</strong>.' . $locatieResponsabil->adresa;
                                $i++; ?></div>
                            <div class="panel-body no-padding centered">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width:20%">Nr. Crt.</th>
                                        <th style="width:20%">Serie</th>
                                        <th style="width:20%">Tip</th>
                                        <th style="width:20%">Expirare Autorizatii</th>
                                        <th style="width:20%">Expirare Metrologii</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($aparateResponsabil[$locatieResponsabil->idlocatie])) {
                                        $j = 1;
                                        foreach ($aparateResponsabil[$locatieResponsabil->idlocatie] as $aparat) {
                                            ?>
                                            <tr>
                                                <td style="width:20%"><?php echo $j; $j++; ?></td>
                                                <td style="width:20%"><?php echo $aparat->seria; ?></td>
                                                <td style="width:20%"><?php echo $aparat->tip; ?></td>
                                                <td style="width:20%"><?php echo $aparat->dtExpAutorizatie; ?></td>
                                                 <td><?php echo $aparat->dtExpMetrologie; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php
                    }
                    ?>
                    <?php /*<table class="table table-responsive table-bordered table-striped" id="table-small">
                                 ?><thead>
                                    <th>Nr. Crt.</th>
                                    <th>Nume Locatie</th>
                                    <th>Serie Aparat</th>
                                    <th>Tip</th>
                                    <th>Detinator Spatiu</th>
                                    <!--<th>Metro</th>
                                    <th>Real</th>-->
                                    <th>Expirare Autorizatii</th>
                                    <th>Expirare Metrologii</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $allAparate = $db->getAllAparateResponsabil($idResponsabil,$_SESSION['operator']);
                                    foreach($allAparate as $objAparateResponsabil){
                                        $aparateResponsabil[$objAparateResponsabil->idLocatie][]=$objAparateResponsabil;
                                    }

                                    $allLocatiiResponsabil = $db->allLocatiiResponsabil($idResponsabil,$_SESSION['operator']);
                                    $i=1;
                                    foreach($allLocatiiResponsabil as $objLocatiiResponsabil){
                                    ?>
                                    <tr>
                                        <td><?php echo $i; $i++; ?></td>
                                        <td><?php echo $objLocatiiResponsabil->denumire; ?></td>
                                        <td>
                                            <?php
                                            if(isset($aparateResponsabil[$objLocatiiResponsabil->idlocatie])){
                                            foreach($aparateResponsabil[$objLocatiiResponsabil->idlocatie] as $aparat){
                                                echo $aparat->seria.'<br/>';
                                            }}
                                            ?>
                                        </td>
                                        <td>
                                             <?php
                                            if(isset($aparateResponsabil[$objLocatiiResponsabil->idlocatie])){
                                            foreach($aparateResponsabil[$objLocatiiResponsabil->idlocatie] as $aparat){
                                                echo $aparat->tip.'<br/>';
                                            }}
                                            ?>
                                        </td>
                                        <td><?php echo '<strong>'.$objLocatiiResponsabil->denumireFirma.'</strong><br/>'.$objLocatiiResponsabil->adresa;; ?></td>

                                        <!--<td>
                                            <?php
                                            if(isset($aparateResponsabil[$objLocatiiResponsabil->idlocatie])){
                                            foreach($aparateResponsabil[$objLocatiiResponsabil->idlocatie] as $aparat){
                                                echo $aparat->tipJocMetrologii.'<br/>';
                                            }}
                                            ?>
                                        </td>
                                        <td>

                                        </td>-->
                                        <td>
                                            <?php
                                            if(isset($aparateResponsabil[$objLocatiiResponsabil->idlocatie])){
                                            foreach($aparateResponsabil[$objLocatiiResponsabil->idlocatie] as $aparat){
                                                echo $aparat->dtExpAutorizatie.'<br/>';
                                            }}
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if(isset($aparateResponsabil[$objLocatiiResponsabil->idlocatie])){
                                            foreach($aparateResponsabil[$objLocatiiResponsabil->idlocatie] as $aparat){
                                                echo $aparat->dtExpMetrologie.'<br/>';
                                            }}
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table> */ ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>