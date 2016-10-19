<?php
require_once "../autoloader.php";

$appSettings = new SessionClass();
$page = new PageClass();
$db = new DataConnection();

if ($appSettings->getOperator() == '') {
    $appSettings->setOperator(1);
}

$personalMapper = new PersonalMapper($db, $appSettings);
$responsabilCurent = $personalMapper->getCurentPersonal();
$operatoriMapper = new OperatoriMapper($db, $appSettings);
$operatorCurent = $operatoriMapper->getCurrentOperator();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Centralizator</title>
    <?php require_once "../includes/header.php" ?>
</head>
<body>
<?php require_once "../includes/menu.php" ?>
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Centalizator :
                <div class="inline">
                    <fieldset>
                        <label>
                            <select name="an" id="an" class="form-control" onchange="schimbaAn(this)">
                                <option
                                    value="<?php echo $appSettings->getAn() ?>"><?php echo $appSettings->getAn(); ?></option>
                                <?php
                                for ($z = 2015; $z < 2020; $z++) {
                                    if ($z != $appSettings->getAn()) {
                                        ?>
                                        <option value="<?php echo $z ?>"><?php echo $z; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </label>
                    </fieldset>
                </div>
                <div class="inline">
                    <fieldset>
                        <label>
                            <select name="luna" id="luna" class="form-control" onchange="schimbaLuna(this)">
                                <option
                                    value="<?php echo $appSettings->getLuna(); ?>"><?php echo $page->getLuna($appSettings->getLuna()) ?></option>
                                <?php
                                for ($i = 1; $i < 13; $i++) {
                                    if ($i != $appSettings->getLuna()) {
                                        ?>
                                        <option
                                            value="<?php echo $i; ?>"><?php echo $page->getLuna($i) ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </label>
                    </fieldset>
                </div>
                <div class="inline">
                    <fieldset>
                        <label>
                            <select class="form-control" name="responsabil" onchange="schimbaResponsabil(this)">
                                <option
                                    value="<?php echo $responsabilCurent->getIdpers() ?>"><?php echo $responsabilCurent->getNick() ?></option>
                                <?php

                                $responsabili = $personalMapper->getResponsabbili();
                                /** @var PersonalEntity $responsabil */
                                foreach ($responsabili as $responsabil) {
                                    echo "<option value='{$responsabil->getIdpers()}'>{$responsabil->getNick()}</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </fieldset>
                </div>
                <div class="inline">
                    <fieldset>
                        <label>
                            <select name="regiune" id="regiune" class="form-control" onchange="changeZona(this)">
                                <option
                                    value="<?php echo ($appSettings->getZona() == '') ? "" : $appSettings->getZona() ?>"><?php echo ($appSettings->getZona() == '') ? "Toate" : $appSettings->getZona() ?></option>
                                <option value="">Toate</option>
                                <?php

                                $regiuni = $responsabilCurent->getRegiuni();
                                foreach ($regiuni as $regiune) {
                                    echo "<option value='{$regiune}'>{$regiune}</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </fieldset>
                </div>
                <div class="inline">
                    <fieldset>
                        <label>
                            <select name="operatori" id="operatori" onchange="" class="form-control">
                                <option value="<?php echo $operatorCurent->getIdoperator() ?>"><?php echo $operatorCurent->getDenFirma() ?></option>
                                <?php

                                $operatori = $operatoriMapper->getOpertatori();
                                /** @var OperatorEntity $operator */
                                foreach ($operatori as $operator) {
                                    echo "<option value='{$operator->getIdoperator()}'>{$operator->getDenFirma()}</option>";
                                }
                                ?>
                            </select>
                        </label>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#operatori").change(function () {
                                   var operator = $(this).val();
                                    $.ajax({
                                        url : DOMAIN+ "/router.php",
                                        type : "POST",
                                        data : {
                                            'operator' : operator
                                        },
                                        success : function(){
                                            location.reload();
                                        }
                                    })
                                });
                            });
                        </script>
                    </fieldset>
                </div>
            </div>
            <div class="panel-body">
                <div class="intro">
                    <?php echo $appSettings->getDataDecorata(); ?><br/>
                    <?php echo strtoupper($operatorCurent->getDenFirma()); ?><br/>
                    <?php echo strtoupper($responsabilCurent->getNick()) ?>
                    <?php echo strtoupper($appSettings->getZona()); ?>
                </div>
                <div id="numarLocatiiSiAparate" style="float: right; margin-right: 100px;">
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var nrLocatii = $("#nrLocatii").val();
                        var nrAparate = $("#nrAparate").val();
                        $("#numarLocatiiSiAparate").html("<h4>" + nrAparate + " Serii - " + nrLocatii + " Locatii</h4>");
                    });
                </script>
                <table class="table table-responsive table-bordered margined-top">
                    <thead>
                    <tr>
                        <th>Nr. Crt.</th>
                        <th>Nume</th>
                        <th>Serii Aparate</th>
                        <th>Expirare Autorizatie</th>
                        <th>Expirare Metrologii</th>
                        <th style="width: 200px">Detinatorul Spatiului</th>
                        <th style="width: 200px">Adresa Punct de Lucru</th>
                        <th>Denumire joc metrologii</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $locatii = $responsabilCurent->getLocatiiByRegiune();
                    $j = 1;
                    /** @var LocatiiEntity $locatie */
                    foreach ($locatii as $locatie) {
                        if ($locatie->isLocatieInchisa() and strpos($locatie->getDenumire(), "Depozit") === FALSE) {
                            ?>
                            <tr>
                                <td style="text-align: center"><?php echo "<strong>{$i}</strong>";
                                    $i++;
                                    echo "<br/>Contract:<br/>{$locatie->getDtInfiintare()}" ?></td>
                                <td style="text-align: center; vertical-align: middle;"><?php echo $locatie->getDenumire(); ?><br/><?php
                                    if($locatie->getDtInchidere() == '01-Jan-1970'){
                                        echo "Locatia este activa";
                                    }else{
                                        echo "Locatie inchisa la data :<br/> {$locatie->getDtInchidere()}";
                                    }
                                    ?></td>
                                <td>
                                    <?php
                                    $aparate = $locatie->getAparateLocatie();
                                    /** @var AparatEntity $aparat */
                                    foreach ($aparate as $aparat) {
                                        $aparat->getAvertizari();
                                        echo $aparat->getSeria() . "<br/>";
                                        $j++;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    /** @var AparatEntity $aparat */
                                    foreach ($aparate as $aparat) {
                                        echo $aparat->returnAvertizari()->getDtExpAutorizatie() . "<br/>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    /** @var AparatEntity $aparat */
                                    foreach ($aparate as $aparat) {
                                        echo $aparat->returnAvertizari()->getDtExpMetrologie() . "<br/>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $firma = $locatie->getFirma();
                                    echo $firma->getDenumire();
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $locatie->getAdresa();
                                    ?>
                                </td>
                                <td class="centered">
                                    <?php
                                    /** @var AparatEntity $aparat */
                                    foreach ($aparate as $aparat) {
                                        echo $aparat->getTip() . "<br/>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <input type="hidden" id="nrLocatii" value="<?php echo $i - 1; ?>"/>
                <input type="hidden" id="nrAparate" value="<?php echo $j - 1; ?>"/>
            </div>
        </div>
    </div>
</body>
</html>


