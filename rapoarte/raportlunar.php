<?php

require_once "../autoloader.php";

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);

$appSettings = new SessionClass();
$page = new PageClass();
/** @var SessionClass $appSettings */
$page->checkLogin($appSettings->getUsername(), $appSettings->getOperator());


$db = new DataConnection();
$locatiiMapper = new LocatiiMaper($db,$appSettings);

$locatie = $locatiiMapper->getLocatie($appSettings->getIdLocatie());
$firma = $locatie->getFirma();


/**
 * Prelucrare indexi noi
 */
if (isset($_POST['submit'])) {
    $post = $_POST;
    $j = 1;
    $valori = [];
    foreach ($post as $key => $value) {
        if ($key != 'submit') {
            $infoCamp = explode('-', $key);
            if ($infoCamp[2] == 'in') {
                $valori[$infoCamp[3]]['in'] = $value;
            } elseif ($infoCamp[2] == 'out') {
                $valori[$infoCamp[3]]['out'] = $value;
            }
        }
    }
    foreach ($valori as $key => $valoare) {
        $contori = new ContorMecanicEntity($db,$appSettings);
        $contori->getRow($key);
        $contori->setIdxInM($valoare['in']);
        $contori->setIdxOutM($valoare['out']);
        $contori->save();
        unset($contori);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
}
if (isset($_POST['submit-jackpot'])) {
    $jackpotDeInserat = new JackpotEntity($db,$appSettings);
    $jackpotDeInserat->setDb($db);
    foreach ($_POST as $key => $value) {
        $info = explode('-', $key);
        if ($info[0] != "submit") {

            if ($info[1] != '') {
                $jackpotDeInserat = $locatie->getJackpotLuna();
                $jackpotDeInserat->setDb($db);
                $jackpotDeInserat->setJackPot($value);
                $jackpotDeInserat->save();
            } else {
                $jackpotDeInserat->setData("{$appSettings->getAn()}-{$appSettings->getLuna()}-20");
                $jackpotDeInserat->setJackPot($value);
                $jackpotDeInserat->setIdlocatie($locatie->getIdlocatie());
                $jackpotDeInserat->insert();
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
}
?>
<html lang="ro">
<head>
    <title>Raport Lunar Indexi</title>
    <?php require_once "../includes/header.php"; ?>
</head>
<body>
<?php require_once "../includes/menu.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4>Situatie lunara : <?php echo $locatie->getDenumire(); ?></h4>

                <div style="display: inline-block">
                    <fieldset>
                        <select name="an" id="an" class="form-control">
                            <option value="<?php echo $appSettings->getAn() ?>"><?php echo $appSettings->getAn(); ?></option>
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
                    </fieldset>
                </div>
                <div style="display: inline-block">
                    <fieldset>
                        <select name="luna" id="luna" class="form-control">
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
                    </fieldset>
                </div>
                <div style="display: inline-block">
                    <button class="btn btn-primary" id="more-info">Arata informatii firma</button>
                    <a href="<?php echo DOMAIN ?>/rapoarte/raportLunarPDF.php" class="btn btn-primary">Download PDF</a>
                    <a href="<?php echo DOMAIN ?>/rapoarte/genereazaLunarWord.php" class="btn btn-primary">Download Word</a>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#an').change(function () {
                            var an = $(this).val();
                            $.ajax({
                                method: 'POST',
                                url: '<?php echo DOMAIN; ?>/router.php',
                                data: {
                                    'an': an
                                },
                                success: function (response) {
                                    window.location.reload();
                                }
                            });
                        });
                        $('#luna').change(function () {
                            var luna = $(this).val();
                            $.ajax({
                                method: 'POST',
                                url: '<?php echo DOMAIN ?>/router.php',
                                data: {
                                    'luna': luna
                                },
                                success: function (response) {
                                    window.location.reload();
                                }
                            });
                        });
                        $('#more-info').click(function (event) {
                            event.preventDefault();
                            $('#info-firma').toggle(300);
                        });
                    });
                </script>
            </div>
            <div class="panel-body" id="info-firma" style="display: none;">
                <div class="panel panel-info">
                    <div class="panel-heading"><h5>Informatii Firma</h5></div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <td><span class="bold">Nume Firma : </span></td>
                                <td><?php echo $firma->getDenumire(); ?></td>
                                <td><span class="bold">Adresa : </span></td>
                                <td><?php echo $locatie->getAdresa(); ?></td>
                            </tr>
                            <tr>
                                <td><span class="bold">CUI:</span></td>
                                <td><?php echo $firma->getCui(); ?></td>
                                <td><span class="bold">J :</span></td>
                                <td><?php echo $firma->getRegComert(); ?></td>
                            </tr>
                            <tr>
                                <td><span class="bold">Manager:</span></td>
                                <td><?php echo $firma->getManager(); ?></td>
                                <td><span class="bold">Data Infiintare :</span></td>
                                <td><?php echo $locatie->getDtInfiintare(); ?></td>
                            </tr>
                            <tr>
                                <td><span class="bold">Id Locatie</span></td>
                                <td colspan="3"><?php echo $locatie->getIdlocatie() ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!$locatie->isLocatieInchisa()) {
            echo "<h1>Locatia nu are indexi pentru luna curenta</h1>";
        }else{
        ?>
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Situatie Lunara Introdusa de picuri.
                        Luna <?php echo $page->getLuna($appSettings->getLuna()) ?></h4></div>
                <div class="panel-body">
                    <table class="table-bordered table-striped col-md-12 tabel-raport text-center">
                        <col>
                        <colgroup span="3"></colgroup>
                        <colgroup span="3"></colgroup>
                        <tr>
                            <td rowspan="2" style="font-weight:bold;">A</td>
                            <td rowspan="2" style="font-weight:bold;">B</td>
                            <th colspan="3" scope="colgroup">C</th>
                            <th colspan="3" scope="colgroup">D</th>
                            <th colspan="3" scope="colgroup">E</th>
                            <th colspan="3" scope="colgroup">F</th>
                            <th>G</th>
                            <th>H</th>
                            <th rowspan="2">Total incasari/<br/>Jackpot</th>
                        </tr>
                        <tr>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">IDX IN<br/> FINAL</th>
                            <th scope="col">Ei</th>
                            <th scope="col">IDX OUT<br/> FINAL</th>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">=11-12-13</th>
                            <th scope="col">Lei</th>
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
                            <td>16 = 14 X 15</td>
                        </tr>
                        <?php
                        $totalIn = 0;
                        $totalOut = 0;

                        $aparate = $locatie->getAparateLocatie();
                        $i = 1;
                        /** @var AparatEntity $aparat */
                        foreach ($aparate as $aparat) {

                            $aparat->getContoriZilnici($appSettings->getAn(), $appSettings->getLuna());
                            /** @var ContorMecanicEntity $contoriInceputLuna */
                            $contoriInceputLuna = $aparat->getInceputLuna();
                            /** @var ContorMecanicEntity $contoriSfarsitLuna */
                            $contoriSfarsitLuna = $aparat->getSfarsitLuna();
                            $var = $aparat->getVariabile();
                            if ($contoriInceputLuna->getIdmec() !== NULL) {
                                ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td><?php echo $aparat->getSeria(); ?></td>
                                    <td><?php echo $contoriInceputLuna->getIdxInM() ?></td>
                                    <td>0</td>
                                    <td><?php echo $contoriInceputLuna->getIdxOutM(); ?></td>
                                    <td><?php echo $contoriSfarsitLuna->getIdxInM(); ?></td>
                                    <td>0</td>
                                    <td><?php echo $contoriSfarsitLuna->getIdxOutM(); ?></td>
                                    <td><?php echo $var->getFmMec(); ?></td>
                                    <td>0</td>
                                    <td><?php echo $var->getFmMec(); ?></td>
                                    <td><?php
                                        echo $diferentaIn = ($contoriSfarsitLuna->getIdxInM() - $contoriInceputLuna->getIdxInM()) * $var->getFmMec();
                                        $totalIn += $diferentaIn;
                                        ?></td>
                                    <td>0</td>
                                    <td><?php
                                        echo $diferentaOut = ($contoriSfarsitLuna->getIdxOutM() - $contoriInceputLuna->getIdxOutM()) * $var->getFmMec();
                                        $totalOut += $diferentaOut;
                                        ?></td>
                                    <td><?php echo $difierentaMare = $diferentaIn - $diferentaOut; ?></td>
                                    <td><?php echo $var->getPiMec(); ?></td>
                                    <td><?php echo $difierentaMare * $var->getPiMec() ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="11" class="text-left">Total Incasari Slot Machine</td>
                            <td><?php echo $totalIn; ?></td>
                            <td>-</td>
                            <td><?php echo $totalOut; ?></td>
                            <td><?php echo $totalIn - $totalOut; ?></td>
                            <td></td>
                            <td><?php echo (count($aparate) > 0) ? ($totalIn - $totalOut) * $var->getPiMec() : 0; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">

            <div class="panel-heading"><h4>Situatie Lunara Preluata de pe teren.
                    Luna <?php echo $page->getLuna($appSettings->getLuna()); ?></h4></div>
            <div class="panel-body">
                <div class="messages">
                    <?php if (isset($mesaj)) {
                        echo $page->printDialog('success', $mesaj);
                    } ?>
                </div>
                <form method="post">
                    <table class="table-bordered table-striped col-md-12 tabel-raport text-center">
                        <col>
                        <colgroup span="3"></colgroup>
                        <colgroup span="3"></colgroup>
                        <tr>
                            <td rowspan="2" style="font-weight:bold;">A</td>
                            <td rowspan="2" style="font-weight:bold;">B</td>
                            <th colspan="3" scope="colgroup">C</th>
                            <th colspan="3" scope="colgroup">D</th>
                            <th colspan="3" scope="colgroup">E</th>
                            <th colspan="3" scope="colgroup">F</th>
                            <th>G</th>
                            <th>H</th>
                            <th rowspan="2">Total incasari/<br/>Jackpot</th>
                        </tr>
                        <tr>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">IDX IN<br/> FINAL</th>
                            <th scope="col">Ei</th>
                            <th scope="col">IDX OUT<br/> FINAL</th>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">I</th>
                            <th scope="col">Ei</th>
                            <th scope="col">Ej</th>
                            <th scope="col">=11-12-13</th>
                            <th scope="col">Lei</th>
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
                            <td>16 = 14 X 15</td>
                        </tr>
                        <?php
                        $totalIn = 0;
                        $totalOut = 0;
                        $i = 1;
                        /** @var AparatEntity $aparat */
                        foreach ($aparate as $aparat) {
                            unset($contoriInceputLuna);
                            unset($contoriSfarsitLuna);
                            $aparat->getContoriTeren();

                            if ($appSettings->getLuna() < 12) {
                                $lunaViitoare = $appSettings->getLuna() + 1;
                                $anulViitor = $appSettings->getAn();
                            } else {
                                $lunaViitoare = 1;
                                $anulViitor = $appSettings->getAn() + 1;
                            }
                            $contoriInceputLuna = $aparat->getIndexByDate($appSettings->getAn(), $appSettings->getLuna());

                            $contoriSfarsitLuna = $aparat->getIndexByDate($anulViitor, $lunaViitoare);
                            if ($contoriInceputLuna->getIdmec() !== NULL) {
                                ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td><?php echo $aparat->getSeria(); ?></td>
                                    <td><label><input type="text"
                                               name="inceput-luna-in-<?php echo $contoriInceputLuna->getIdmec() ?>"
                                               value="<?php echo $contoriInceputLuna->getIdxInM() ?>"
                                               class="width-100"/></label>
                                    </td>
                                    <td>0</td>
                                    <td><label><input type="text"
                                               name="inceput-luna-out-<?php echo $contoriInceputLuna->getIdmec() ?>"
                                               value="<?php echo $contoriInceputLuna->getIdxOutM(); ?>"
                                               class="width-100"/></label>
                                    </td>
                                    <td><label><input type="text"
                                               name="sfarsit-luna-in-<?php echo $contoriSfarsitLuna->getIdmec() ?>"
                                               value="<?php echo $contoriSfarsitLuna->getIdxInM(); ?>"
                                               class="width-100"/></label>
                                    </td>
                                    <td>0</td>
                                    <td><label><input type="text"
                                               name="sfarsit-luna-out-<?php echo $contoriSfarsitLuna->getIdmec() ?>"
                                               value="<?php echo $contoriSfarsitLuna->getIdxOutM(); ?>"
                                               class="width-100"/></label>
                                    </td>
                                    <td><?php echo $var->getFmMec(); ?></td>
                                    <td>0</td>
                                    <td><?php echo $var->getFmMec(); ?></td>
                                    <td><?php
                                        echo $diferentaIn = ($contoriSfarsitLuna->getIdxInM() - $contoriInceputLuna->getIdxInM()) * $var->getFmMec();
                                        $totalIn += $diferentaIn;
                                        ?></td>
                                    <td>0</td>
                                    <td><?php
                                        echo $diferentaOut = ($contoriSfarsitLuna->getIdxOutM() - $contoriInceputLuna->getIdxOutM()) * $var->getFmMec();
                                        $totalOut += $diferentaOut;
                                        ?></td>
                                    <td><?php echo $difierentaMare = $diferentaIn - $diferentaOut; ?></td>
                                    <td><?php echo $var->getPiMec(); ?></td>
                                    <td><?php echo $difierentaMare * $var->getPiMec() ?></td>
                                </tr>
                                <?php
                            }
                        } ?>
                        <tr>
                            <td colspan="11" class="text-left">Total Incasari Slot Machine</td>
                            <td><?php echo $totalIn; ?></td>
                            <td>-</td>
                            <td><?php echo $totalOut; ?></td>
                            <td><?php echo $totalIn - $totalOut; ?></td>
                            <td></td>
                            <td><?php echo (count($aparate) > 0) ? ($totalIn - $totalOut) * $var->getPiMec() : 0; ?></td>
                        </tr>
                        <?php
                        if ($locatie->areJackpot()) {
                            /** @var JackpotEntity $jackpot */
                            $jackpot = $locatie->getJackPotLuna()
                            ?>
                            <tr>
                                <td colspan="16" class="text-left">JackPot</td>

                                <td><?php echo $jackpot->getJackPot(); ?></td>
                            </tr>
                            <tr>
                                <td class="text-left" colspan="16">TOTAL - JACKPOT</td>
                                <td><?php echo (count($aparate) > 0) ? (($totalIn - $totalOut) * $var->getPiMec()) - $jackpot->getJackPot() : 0; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <button type="submit" name="submit" class="btn btn-primary" value="1">Salveaza Indexi</button>
                </form>
                <?php if ($locatie->areJackpot()) { ?>
                    <form method="POST" name="Jackpot" class="inline">
                        <input name="jackpot-<?php echo $jackpot->getIdjackpot(); ?>"
                               value="<?php echo $jackpot->getJackPot() ?>" class="width-100">
                        <button type="submit" name="submit-jackpot" value="jackpot" class="btn btn-primary">Modifica
                            Jackpot
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
</body>
</html>
