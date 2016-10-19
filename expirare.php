<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
$appSettings = $session = new SessionClass();
$page = new PageClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
if (!isset($_GET['an'])) {
    $an = date('Y');
} else {
    $get = $db->sanitizePost($_GET);
    $an = $get['an'];
}
if (!isset($_GET['tip'])) {
    $tip = 'dtExpMetrologie';
} else {
    $tip = $_GET['tip'];
}
$tipuri = ['tip1' => 'dtExpMetrologie', 'tip2' => 'dtExpAutorizatie'];
$database = new DataConnection();
$locatiiMapper = new LocatiiMaper($database, $session);
?>
<!DOCTYPE>
<html>
<head>
    <title>Expirare Metrologii</title>
    <?php require_once('includes/header.php'); ?>
    <style>
    </style>
</head>
<body>
<?php require_once('includes/menu.php'); ?>


<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="container-600">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="inline"><span style="display: inline-block; width : 32%;">Metrologii</span></h4>
            <fieldset style="width: 20%; display: inline-block">
                <select name="an" id="an" class="form-control">
                    <?php
                    $ani = $db->getDistinctAni('dtExpMetrologie');
                    foreach ($ani as $key => $value) {
                        if ($an == $value) {
                            ?>
                            <option value='<?php echo $value ?>'><?php echo $value; ?></option>
                            <?php
                        }
                    }
                    foreach ($ani as $key => $value) {
                        if ($an != $value) {
                            ?>
                            <option value='<?php echo $value ?>'><?php echo $value; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </fieldset>
            <script type='text/javascript'>
                $(document).on('change', '#an', function () {
                    var an = $('#an').val();
                    window.location.href = '?an=' + an;
                });
            </script>
        </div>
        <?php
        $avertizari = $db->getAvertizariTable('dtExpMetrologie', $an);
        $luni = [];
        $ampera = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0];
        $redlong = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0];
        $erori = [];
        foreach ($avertizari as $avertizare) {
            $data = explode('-', $avertizare->dtExpMetrologie);
            if (isset($data[1])) {
                switch ($data['1']) {
                    case 1 :
                        $luni[1][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[1] += 1;
                        } else {
                            $redlong[1] += 1;
                        }
                        break;
                    case 2 :
                        $luni[2][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[2] += 1;
                        } else {
                            $redlong[2] += 1;
                        }
                        break;
                    case 3 :
                        $luni[3][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[3] += 1;
                        } else {
                            $redlong[3] += 1;
                        }
                        break;
                    case 4 :
                        $luni[4][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[4] += 1;
                        } else {
                            $redlong[4] += 1;
                        }
                        break;
                    case 5 :
                        $luni[5][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[5] += 1;
                        } else {
                            $redlong[5] += 1;
                        }
                        break;
                    case 6 :
                        $luni[6][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[6] += 1;
                        } else {
                            $redlong[6] += 1;
                        }
                        break;
                    case 7 :
                        $luni[7][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[7] += 1;
                        } else {
                            $redlong[7] += 1;
                        }
                        break;
                    case 8 :
                        $luni[8][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[8] += 1;
                        } else {
                            $redlong[8] += 1;
                        }
                        break;
                    case 9 :
                        $luni[9][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[9] += 1;
                        } else {
                            $redlong[9] += 1;
                        }
                        break;
                    case 10 :
                        $luni[10][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[10] += 1;
                        } else {
                            $redlong[10] += 1;
                        }
                        break;
                    case 11 :
                        $luni[11][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[11] += 1;
                        } else {
                            $redlong[11] += 1;
                        }
                        break;
                    case 12 :
                        $luni[12][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[12] += 1;
                        } else {
                            $redlong[12] += 1;
                        }
                        break;
                }
            } else {
                $erori[] = $avertizare;
            }
        }
        $j = 1;
        ?>
        <table class="table table-responsive table-bordered">
            <thead>
            <tr>
                <th>Nr.Crt</th>
                <th>Luna</th>
                <th>Ampera</th>
                <th>Redlong</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalAmpera = 0;
            $totalRedlong = 0;
            for ($k = 1; $k < 13; $k++) {
                ?>
                <tr>
                    <td><?php echo $k; ?></td>
                    <td><?php echo $page->getLuna($k); ?></td>
                    <td class="ampera" data-luna="<?php echo $k; ?>"
                        data-an="<?php echo $an; ?>"
                        data-tip="dtExpMetrologie"><?php echo $ampera[$k];
                        $totalAmpera += $ampera[$k]; ?></td>
                    <td class="redlong" data-luna="<?php echo $k ?>"
                        data-an="<?php echo $an; ?>"
                        data-tip="dtExpMetrologie"><?php echo $redlong[$k];
                        $totalRedlong += $redlong[$k]; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="2" style="text-align: center;">Total</td>
                <td><?php echo $totalAmpera; ?></td>
                <td><?php echo $totalRedlong; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="container-600">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="inline"><span style="display: inline-block; width : 32%;">Autorizatii</span></h4>
            <fieldset style="width: 20%; display: inline-block">
                <select name="an" id="an" class="form-control">
                    <?php
                    $ani = $db->getDistinctAni('dtExpAutorizatie');
                    foreach ($ani as $key => $value) {
                        if ($an == $value) {
                            ?>
                            <option value='<?php echo $value ?>'><?php echo $value; ?></option>
                            <?php
                        }
                    }
                    foreach ($ani as $key => $value) {
                        if ($an != $value) {
                            ?>
                            <option value='<?php echo $value ?>'><?php echo $value; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </fieldset>
            <script type='text/javascript'>
                $(document).on('change', '#an', function () {
                    var an = $('#an').val();
                    window.location.href = '?an=' + an;
                });
            </script>
        </div>
        <?php
        $avertizari = $db->getAvertizariTable('dtExpAutorizatie', $an);
        $luni = [];
        $ampera = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0];
        $redlong = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0];
        $erori = [];
        foreach ($avertizari as $avertizare) {
            $data = explode('-', $avertizare->dtExpAutorizatie);
            if (isset($data[1])) {
                switch ($data['1']) {
                    case 1 :
                        $luni[1][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[1] += 1;
                        } else {
                            $redlong[1] += 1;
                        }
                        break;
                    case 2 :
                        $luni[2][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[2] += 1;
                        } else {
                            $redlong[2] += 1;
                        }
                        break;
                    case 3 :
                        $luni[3][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[3] += 1;
                        } else {
                            $redlong[3] += 1;
                        }
                        break;
                    case 4 :
                        $luni[4][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[4] += 1;
                        } else {
                            $redlong[4] += 1;
                        }
                        break;
                    case 5 :
                        $luni[5][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[5] += 1;
                        } else {
                            $redlong[5] += 1;
                        }
                        break;
                    case 6 :
                        $luni[6][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[6] += 1;
                        } else {
                            $redlong[6] += 1;
                        }
                        break;
                    case 7 :
                        $luni[7][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[7] += 1;
                        } else {
                            $redlong[7] += 1;
                        }
                        break;
                    case 8 :
                        $luni[8][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[8] += 1;
                        } else {
                            $redlong[8] += 1;
                        }
                        break;
                    case 9 :
                        $luni[9][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[9] += 1;
                        } else {
                            $redlong[9] += 1;
                        }
                        break;
                    case 10 :
                        $luni[10][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[10] += 1;
                        } else {
                            $redlong[10] += 1;
                        }
                        break;
                    case 11 :
                        $luni[11][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[11] += 1;
                        } else {
                            $redlong[11] += 1;
                        }
                        break;
                    case 12 :
                        $luni[12][] = $avertizare;
                        if ($avertizare->idOperator == 1) {
                            $ampera[12] += 1;
                        } else {
                            $redlong[12] += 1;
                        }
                        break;
                }
            } else {
                $erori[] = $avertizare;
            }
        }
        $j = 1;
        ?>
        <table class="table table-responsive table-bordered">
            <thead>
            <tr>
                <th>Nr.Crt</th>
                <th>Luna</th>
                <th>Ampera</th>
                <th>Redlong</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalAmpera = 0;
            $totalRedlong = 0;
            for ($k = 1; $k < 13; $k++) {
                ?>
                <tr>
                    <td><?php echo $k; ?></td>
                    <td><?php echo $page->getLuna($k); ?></td>
                    <td class="ampera" data-luna="<?php echo $k; ?>"
                        data-an="<?php echo $an; ?>"
                        data-tip="dtExpAutorizatie"><?php echo $ampera[$k];
                        $totalAmpera += $ampera[$k]; ?></td>
                    <td class="redlong" data-luna="<?php echo $k ?>"
                        data-an="<?php echo $an; ?>"
                        data-tip="dtExpAutorizatie"><?php echo $redlong[$k];
                        $totalRedlong += $redlong[$k]; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="2" style="text-align: center;">Total</td>
                <td><?php echo $totalAmpera; ?></td>
                <td><?php echo $totalRedlong; ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script type='text/javascript'>
    $(document).on('click', '.ampera', function () {

        var luna = $(this).attr('data-luna');
        var an = $(this).attr('data-an');
        var tip = $(this).attr('data-tip');
        $.ajax({
            type: "POST",
            url: 'ajax/tabelExpirari.php',
            data: {
                'luna': luna,
                'an': an,
                'tip': tip,
                'operator': 1
            },
            success: function (result) {
                $('.modal').modal();
                $('.modal-body').html(result);
            }
        });
    });
    $(document).on('click', '.redlong', function () {

        var luna = $(this).attr('data-luna');
        var an = $(this).attr('data-an');
        var tip = $(this).attr('data-tip');
        $.ajax({
            type: "POST",
            url: 'ajax/tabelExpirari.php',
            data: {
                'luna': luna,
                'an': an,
                'tip': tip,
                'operator': 2
            },
            success: function (result) {
                $('.modal').modal();
                $('.modal-body').html(result);
            }
        });
    });
</script>
<div class="clearfix"></div>
<div class="container-600">
    <div class="panel panel-primary">
        <div class="panel-heading">Cerere Efectuare Tehnica</div>
        <div class="panel-body">
            <form method="POST" action="<?php echo DOMAIN ?>/cereri/gereneazaCerereVerificare.php">
                <label>
                    Operator
                    <select name="operator" class="form-control" id="operatorTehnic">
                        <?php
                        $operatoriMapper = new OperatoriMapper($database, $appSettings);
                        $operatori = $operatoriMapper->getOpertatori();
                        /** @var OperatorEntity $operator */
                        foreach ($operatori as $operator) {
                            ?>
                            <option
                                value="<?php echo $operator->getIdoperator(); ?>"><?php echo $operator->getDenFirma(); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </label>
                <label>
                    Luna
                    <select class="form-control" id="lunaa" name="luna[]" multiple>
                        <?php
                        for ($i = 1; $i < 13; $i++) {
                            echo "<option value='{$i}'>{$appSettings->getLunaInRomana($i)}</option>";
                        }
                        ?>
                    </select>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#lunaa").change(function (event) {
                                event.preventDefault();
                                var luni = $(this).val();
                                var operator = $("#operatorTehnic").val();
                                $.ajax({
                                    url: DOMAIN + "/ajax/getZoneMetrologii.php",
                                    type: "POST",
                                    data: {
                                        "operator": operator,
                                        "luni": luni
                                    },
                                    success: function (response) {
//                                        alert(response);
//                                        console.log(response);
                                        var regiuni = $("#regiune");
                                        regiuni.find("option").remove().end();
                                        regiuni.append(response);
                                    }
                                })
                            });
                        });
                    </script>
                </label>
                <label>
                    Regiune
                    <select name="regiune" id="regiune" class="form-control">
                        <option value="-">Va rugam sa selectari operatorul si luna</option>
                    </select>
                </label>
                <label>
                    &nbsp;
                    <button type="submit" value="1" class="btn btn-primary col-md-12">Genereaza Cerere</button>
                </label>
            </form>
        </div>
    </div>
</div>
<div class="container-600">
    <div class="panel panel-primary">
        <div class="panel-heading">Genereaza Cerere de Autorizare</div>
        <div class="panel-body">
            <form method="get" action="<?php echo DOMAIN . '/genereazaAnuntari.php'; ?>">
                <fieldset>
                    <label for="operator">Operator</label>
                    <select name="id" class="form-control" id="operator">
                        <?php
                        $operatoriMapper = new OperatoriMapper($database, $session);
                        $operatori = $operatoriMapper->getOpertatori();
                        /** @var OperatorEntity $operator */
                        foreach ($operatori as $operator) {
                            ?>
                            <option
                                value="<?php echo $operator->getIdoperator(); ?>"><?php echo $operator->getDenFirma(); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label for="an">An</label>
                    <select name="an" id="an" class="form-control">
                        <option value="<?php echo $session->getAn() ?>"><?php echo $session->getAn(); ?></option>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Luna
                        <select name="luna" class="form-control">
                            <option
                                value="<?php echo $session->getLuna() ?>"><?php echo $page->getLuna($session->getLuna()); ?></option>
                            <?php
                            $luni = $page->getLuniArray();
                            foreach ($luni as $key => $value) {
                                if ($key != $session->getLuna()) {
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </label>
                </fieldset>
                <fieldset>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary form-control">Genereaza</button>
                </fieldset>
            </form>
        </div>
    </div>
</div>

</body>
</html>
