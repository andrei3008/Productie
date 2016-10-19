<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
$appSettings = $session = new SessionClass();
$page = new PageClass();
if (isset($_POST['submit'])) {
    $judet = $db->real_escape_string($_POST['judet']);
    $responsabil = $db->real_escape_string($_POST['responsabil']);
    $locatie = $db->real_escape_string($_POST['locatie']);
    $serie = $db->real_escape_string($_POST['serie']);
    $detinator = $db->real_escape_string($_POST['detinator']);
    $adresa = $db->real_escape_string($_POST['adresa']);
    $metrologie = $db->real_escape_string($_POST['metrologie']);
    $contractInternet = $db->real_escape_string($_POST['contractInternet']);
}
?>
<!DOCTYPE>
<html>
    <head>
        <title>Cautare generala</title>
        <?php require_once('includes/header.php'); ?>
        <link rel="stylesheet" href="js/dataTable/dataTable.css">
        <script src="js/dataTable/dataTables.js"></script>
    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Parametrii de cautare :</div>
                    <div class="panel-body">
                        <form method="POST" class="form-group">
                            <div class="col-md-6">
                                <fieldset>
                                    <label for="judet">Judet</label>
                                    <input type="text" name="judet" id="judetAuto" class="form-control" placeholder="Regiune" <?php echo (isset($_POST['submit'])) ? 'value="' . $judet . '"' : ''; ?>/>
                                    <script>
                                        $(function () {
                                            var judetDisponibili = [
                                            <?php
                                                $regiunii = $db->getDistinctRegionsName();
                                            foreach ($regiunii as $objRegiune) {
                                                echo '"' . $objRegiune->regiune . '",';
                                            }
                                            ?>
                                            ];
                                            $("#judetAuto").autocomplete({
                                                source: judetDisponibili
                                            });
                                        });
                                    </script>
                                </fieldset>
                                <fieldset>
                                    <label for="responsabil">Responsabil</label>
                                    <input type="text" name="responsabil" placeholder="Responsabil Zonal" id="autoResponsabil" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $responsabil . '"' : ''; ?>/>
                                    <script>
                                        $(function () {
                                            var responsabiliDisponibili = [
                                            <?php
                                            $resultResponsabili = $db->getPersonalNick();
                                            foreach ($resultResponsabili as $objResponsabili) {
                                                echo '"' . $objResponsabili->nick . '",';
                                            }
                                            ?>
                                            ];
                                            $("#autoResponsabil").autocomplete({
                                                source: responsabiliDisponibili
                                            });
                                        });
                                    </script>
                                </fieldset>
                                <fieldset>
                                    <label for="locatie">Nume locatie: </label>
                                    <input id="locatiiDisponibile" type="text" name="locatie" placeholder="Nume locatie" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $locatie . '"' : ''; ?>/>
                                    <script>
                                        $(function () {
                                            var LocatieDisponibilila = [
                                            <?php
                                            $resultLocatii = $db->getLocationNames();
                                            foreach ($resultLocatii as $objLocatii) {
                                                echo '"' . $objLocatii->denumire . '",';
                                            }
                                            ?>
                                            ];
                                            $("#locatiiDisponibile").autocomplete({
                                                source: LocatieDisponibilila
                                            });
                                        });
                                    </script>
                                </fieldset>
                                <fieldset>
                                    <label for="serie">Serie</label>
                                    <input type="text" name="serie" placeholder="Serie Aparat" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $serie . '"' : ''; ?>/>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <label for="detinator">Detinator Spatiu</label>
                                    <input type="text" name="detinator" placeholder="Detinator Spatiu" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $detinator . '"' : ''; ?>/>
                                </fieldset>
                                <fieldset>
                                    <label for="adresa">Adresa</label>
                                    <input type="text" name="adresa" placeholder="Adresa Spatiu" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $adresa . '"' : ''; ?>/>
                                </fieldset>
                                <fieldset>
                                    <label for="metrologie">Metrologia</label>
                                    <input type="text" name="metrologie" placeholder="Metrologia" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $metrologie . '"' : ''; ?>/>
                                </fieldset>
                                <fieldset>
                                    <label for="contractInternet">Contract Internet</label>
                                    <input type="text" name="contractInternet" placeholder="Contract Internet" class="form-control" <?php echo (isset($_POST['submit'])) ? 'value="' . $contractInternet . '"' : ''; ?>/>
                                </fieldset>
                            </div>
                            <div class="col-md-12 margined-top">
                                <input type="submit" name="submit" value="Cauta" class="btn btn-primary btn-md right"/>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_POST['submit'])) {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">Rezultate Cautare</div>
                        <div class="panel-body table-responsive">
                            <table class="table table-bordered table-responsive" id="table-small">
                                <thead>
                                    <tr>
                                        <th style="width: 80px">Judet</th>
                                        <th style="width: 100px">Responsabil</th>
                                        <th>Nume Locatie</th>
                                        <th>Serie</th>
                                        <th>Detinator Spatiu</th>
                                        <th>Adresa</th>
                                        <th>Metrologie</th>
                                        <th>Contract Internet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $resultCautare = $db->getSearch($judet,$responsabil,$locatie,$serie,$detinator,$adresa,$metrologie,$contractInternet);
                                    if (count($resultCautare) > 0 ) {
                                        foreach ($resultCautare as $objCautare) {
                                            ?>
                                            <tr>
                                                <td><?php echo $objCautare->regiune; ?></td>
                                                <td><?php echo $objCautare->nick; ?></td>
                                                <td><?php echo $objCautare->numeLocatie; ?></td>
                                                <td><?php echo $objCautare->seria; ?></td>
                                                <td><?php echo $objCautare->numeFirma; ?></td>
                                                <td><?php echo $objCautare->adresa; ?></td>
                                                <td><?php echo $objCautare->tip; ?></td>
                                                <td><?php echo $objCautare->contractInternet; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
<script>
    $(document).ready(function() {
        $.extend( true, $.fn.dataTable.defaults, {
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
            "oLanguage": {
                "sLengthMenu": "<span>Show entries:</span> _MENU_",
                "oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
            }
        });
        oDataTable = $('#table-small').dataTable();
    });
</script>
<style type="text/css">
    tr td:last-child {
        text-align: right;
    }
    .table > tbody > tr > td {
        padding: 4px 9px
    }
    .DataTables_sort_wrapper {
        text-align: center;
    }
</style>