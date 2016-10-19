<!DOCTYOPE html>
<?php

require_once "../autoloader.php";
$appSettings = new SessionClass();
$database = new DataConnection();
$page = new PageClass();

$locatie = new LocatiiEntity($database, $appSettings);
$locatie->getLocatieCurenta();
$operator = new OperatorEntity($database, $appSettings);
$operator->getCurrentOperator();


?>
<html>
<head>
    <title>Raport Zilnic <?php echo $locatie->getDenumire() ?></title>
    <?php require_once "../includes/header.php"; ?>
</head>
<body>
<?php require_once "../includes/menu.php"; ?>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="col-md-6">
                            <label>
                                <select name="an" id="an" class="form-control" onchange="schimbaAn(this)">
                                    <option
                                        value="<?php echo $appSettings->getAn() ?>"><?php echo $appSettings->getAn() ?></option>
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
                        <fieldset class="col-md-6">
                            <label>
                                <select name="luna" id="luna" class="form-control" onchange="schimbaLuna(this)">
                                    <option
                                        value="<?php echo $appSettings->getLuna() ?>"><?php echo $page->getLuna($appSettings->getLuna()) ?></option>
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
                </div>
                <div class="row text-center">
                    <?php
                    $maximZile = $appSettings->getDaysInCurrentMonth();
                    for ($i = 1; $i <= $maximZile; $i++) {
                        $data = strtotime("{$appSettings->getAn()}-{$appSettings->getLuna()}-$i");
                        $dayOfWeek = date('N', $data);
                        $curentDate = new DateTime();
                        $dayOfMonth = new DateTime("{$appSettings->getAn()}-{$appSettings->getLuna()}-$i");
                        if ($curentDate > $dayOfMonth) {
                            $customClass = ($i == $appSettings->getZi()) ? "selected" : "";
                            echo "<a href='#' class='btn btn-primary btn-zile {$customClass}' data-zi='{$i}' onclick='changeZi(this)' >{$i}<br/>{$appSettings->getShortDay($dayOfWeek)}</a>";
                        } else {
                            echo "<a href='#' class='btn btn-disabled btn-zile' onclick='doNothing(event)'>{$i}<br/>{$appSettings->getShortDay($dayOfWeek)}</a>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-bordered col-md-12 no-bold text-center">
                    <tr>
                        <td colspan="15" class="text-left">
                            Organizator : <?php echo $operator->getDenFirma(); ?><br/>
                            Domiciliu Fiscal : <?php echo $operator->getDomiciliuFiscal(); ?><br/>
                            Cod de identificare fiscala : <?php echo $operator->getCui(); ?><br/>
                            Nr. de inregistrare la registrul comertului : <?php echo $operator->getRegComert(); ?><br/>
                            Capital Social : <?php echo $operator->getCapitalSocial(); ?><br/>
                            Licenta de organizare activitate slot machine : <?php echo $operator->getLicenta(); ?><br/>
                            Adresa punctului de lucru : <?php echo $locatie->getAdresa(); ?>
                        </td>
                        <td class="v-align-center text-center" colspan="4">
                            Data
                            : <?php echo "{$appSettings->getZi()} / {$page->getLuna($appSettings->getLuna())} / {$appSettings->getAn()}"; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="19" class="text-center">
                            <h4>SITUATIA INCASARILOR (VENITURILOR) ZILNICE</h4>
                            obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)
                        </td>
                    </tr>
                    <tr class="no-bold text-center">
                        <td>Nr. Crt.</td>
                        <td>Seria mijlocului de joc</td>
                        <td colspan="3">Indexul contoarelor la inceput (Si)</td>
                        <td colspan="3">Indexul contoarelor la sfarsit (Sf)</td>
                        <td colspan="3">Factor de multiplicare (F)</td>
                        <td colspan="3">Diferenta dintre indexurile contoarelor (D) = (Sf - Si) x F</td>
                        <td>Soldul impulsurilor</td>
                        <td>Pretul / impuls</td>
                        <td>Taxa de participare colectata (T)</td>
                        <td>Total Plati efectuate catre jucatori (P)</td>
                        <td>Incasari (venituri) (lei)</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>I</td>
                        <td>Ej</td>
                        <td>Ei</td>
                        <td>I</td>
                        <td>Ej</td>
                        <td>Ei</td>
                        <td>I</td>
                        <td>Ej</td>
                        <td>Ei</td>
                        <td>I</td>
                        <td>Ej</td>
                        <td>Ei</td>
                        <td>= 11 - 12 - 13</td>
                        <td>lei</td>
                        <td>= 11 * 15</td>
                        <td>= 13 * 15</td>
                        <td>= 14 * 15 = 16 - 17</td>
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
                        <td>16</td>
                        <td>17</td>
                        <td>18</td>
                    </tr>
                    <?php
                    $aparate = $locatie->getAparateLocatie();
                    $grandTotal = 0;

                    $i=1;
                    /** @var AparatEntity $aparat */
                    foreach($aparate as $aparat){
                        $aparat->getContoriZilnici($appSettings->getAn(),$appSettings->getLuna());
                        $dataAzi = new DateTime();
                        $dataIeri = $dataAzi->sub(new DateInterval("P1D"));
                        $contoriIeri = $aparat->getContorByZi(($appSettings->getZi()-1));
                        $contoriAzi = $aparat->getContorByZi($appSettings->getZi());
                        $variabile = $aparat->getVariabile();
                        $dIn = $contoriAzi->getIdxInM() - $contoriIeri->getIdxInM();
                        $dOut = $contoriAzi->getIdxOutM() - $contoriIeri->getIdxOutM();
                        $soldImpuls = $dIn - $dOut;
                        $tParticipare = $dIn * $variabile->getPiMec();
                        $tPlati = $dOut * $variabile->getPiMec();
                        $totalAparat = $tParticipare - $tPlati;
                        $grandTotal += $totalAparat;
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $aparat->getSeria(); ?></td>
                            <td><?php echo $contoriIeri->getIdxInM() ?></td>
                            <td>0</td>
                            <td><?php echo $contoriIeri->getIdxOutM() ?></td>
                            <td><?php echo $contoriAzi->getIdxInM(); ?></td>
                            <td>0</td>
                            <td><?php echo $contoriAzi->getIdxOutM() ?></td>
                            <td><?php echo $variabile->getFmMec() ?></td>
                            <td>0</td>
                            <td><?php echo $variabile->getFmMec() ?></td>
                            <td><?php echo $dIn; ?></td>
                            <td>0</td>
                            <td><?php echo $dOut; ?></td>
                            <td><?php echo $soldImpuls; ?></td>
                            <td><?php echo $variabile->getPiMec(); ?></td>
                            <td><?php echo $tParticipare; ?></td>
                            <td><?php echo $tPlati; ?></td>
                            <td><?php echo $totalAparat; ?></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                    <tr>
                        <td colspan="18">INCASARI (VENITURI) ZILNICE SLOT MACHINE</td>
                        <td><?php echo $grandTotal; ?></td>
                    </tr>
                    <?php if($locatie->areJackpot()){
                        $jackpot =$locatie->getJackpotZilnic();
                        ?>
                        <tr>
                            <td colspan="18">TOTAL CASTIGURI JACKPOT ACORDATE ZILNIC <br/> (netransferate in pozitia credit a unuia dintre mijloacele de joc slot machine)</td>
                            <td><?php echo $jackpot->getJackPot(); $grandTotal -= $jackpot->getJackPot(); ?></td>
                        </tr>
                    <?php
                    } ?>
                    <tr>
                        <td colspan="18">TOTAL INCASARI (VENITURI) ZILNICE</td>
                        <td><?php echo $grandTotal; ?></td>
                    </tr>
                </table>

                <a href="genereazaPDFZilnic.php" class="btn btn-primary">Genereaza PDF</a>
                <a href="genereazaWordZilnic.php" class="btn btn-primary">Genereaza Word</a>
            </div>
        </div>
    </div>
</body>
</html>
