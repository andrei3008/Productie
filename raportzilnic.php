<!DOCTYPE>
<?php
require_once "autoloader.php";
$session = new SessionClass();

$page = new PageClass();
$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$an = $session->getAn();

$locatie =  new LocatiiEntity($db,$session);
$locatie->getLocatie($session->getIdLocatie());
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Raport Zilnic <?php echo $locatie->getDenumire(). ' ' . $session->getZi() . '-' . $session->getLuna() . '-' . $session->getAn(); ?></title>
        <?php require_once('includes/header.php'); ?>
        <style>

        </style>

    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">

                        </div>
                        <div class="panel-body" id="printable" class="printee"> 
                            <div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <p>Organizator: </p>
                                        <p>Detinatorul Spatiului:</p>
                                        <p>Adresa:</p>
                                        <p>Sala din:</p>
                                        <p>Data:</p>
                                    </div>
                                    <div class="col-md-10">
                                        <p><?php echo $_SESSION['com_name']; ?></p>
                                        <p><?php echo $locatie->getDenumire(); ?></p>
                                        <p><?php echo $locatie->getAdresa(); ?></p>
                                        <p><?php echo $locatie->getRegiune(); ?></p>
                                        <p><?php echo $session->getData(); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="centered">Situatia incasarilor zilnice</h2><br/>
                                        <h4 class="centered">obtinute din activitatea de exploatare a sistemelor de tip slot machine (lei)</h4>
                                    </div>
                                </div>
                                <table class="table-bordered table-striped table-condensed cf col-md-12 tabel-raport">
                                    <col>
                                    <colgroup span="3"></colgroup>
                                    <colgroup span="3"></colgroup>
                                    <tr>
                                        <td rowspan="2" class="centered" style="font-weight:bold;">A</td>
                                        <td rowspan="2" class="centered" style="font-weight:bold;">B</td>
                                        <th colspan="3" scope="colgroup" class="centered">C</th>
                                        <th colspan="3" scope="colgroup" class="centered">D</th>
                                        <th colspan="3" scope="colgroup" class="centered">E</th>
                                        <th colspan="3" scope="colgroup" class="centered">F</th>
                                        <th class="centered">G</th>
                                        <th class="centered">H</th>
                                        <th rowspan="2" class="centered">Total incasari/<br/>Jackpot</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="centered">I</th>
                                        <th scope="col" class="centered">Ei</th>
                                        <th scope="col" class="centered">Ej</th>
                                        <th scope="col" class="centered">I</th>
                                        <th scope="col" class="centered">Ei</th>
                                        <th scope="col" class="centered">Ej</th>
                                        <th scope="col" class="centered">I</th>
                                        <th scope="col" class="centered">Ei</th>
                                        <th scope="col" class="centered">Ej</th>
                                        <th scope="col" class="centered">I</th>
                                        <th scope="col" class="centered">Ei</th>
                                        <th scope="col" class="centered">Ej</th>
                                        <th scope="col" class="centered">=11-12-13</th>
                                        <th scope="col" class="centered">Lei</th>
                                    </tr>
                                    <tr>
                                        <td class="centered">0</td>
                                        <td class="centered">1</td>
                                        <td class="centered">2</td>
                                        <td class="centered">3</td>
                                        <td class="centered">4</td>
                                        <td class="centered">5</td>
                                        <td class="centered">6</td>
                                        <td class="centered">7</td>
                                        <td class="centered">8</td>
                                        <td class="centered">9</td>
                                        <td class="centered">10</td>
                                        <td class="centered">11</td>
                                        <td class="centered">12</td>
                                        <td class="centered">13</td>
                                        <td class="centered">14</td>
                                        <td class="centered">15</td>
                                        <td class="centered">16 = 14 X 15</td>
                                    </tr>
                                    <?php
                                    $aparateAzi = $db->getIndexByDate($an, $session->getLuna(), $session->getZi(), $locatie->getIdlocatie());
                                    foreach($aparateAzi as $aparate){
                                        $aparateIeri[] = $db->getMaxIdBeforeDate($an,$luna, $zi,$aparate->idAparat );
                                    }
                                    $j = 1;
                                    $total = 0;
                                    $total11 = 0;
                                    $total13 = 0;
                                    $total14 = 0;
                                    foreach ($aparateAzi as $aparat) {
                                        $objVariabile = $db->getVariabile($an, $luna, $zi, $aparat->idAparat);
                                        ?>
                                        <tr>
                                            <td class="centered" id="0"><?php
                                                echo $j;
                                                
                                                ?></td>
                                            <td><?php echo $aparat->seria; ?></td>
                                            <td><?php echo $camp2 = $aparateIeri[$j-1]->idxInM; ?></td>
                                            <td>0</td>
                                            <td><?php echo $camp4 = $aparateIeri[$j-1]->idxOutM; ?></td>
                                            <td><?php echo $camp5 = $aparat->idxInM; ?></td>
                                            <td>0</td>
                                            <td><?php echo $camp7 = $aparat->idxOutM; ?></td>
                                            <td><?php echo $objVariabile->fm_mec; ?></td>
                                            <td>0</td>
                                            <td><?php echo $objVariabile->fm_mec; ?></td>
                                            <td><?php
                                                echo $camp11 = ($camp5 - $camp2) * $objVariabile->fm_mec;
                                                $total11 +=$camp11;
                                                ?></td>
                                            <td>0</td>
                                            <td><?php
                                                echo $camp13 = ($camp7 - $camp4) * $objVariabile->fm_mec;
                                                $total13 +=$camp13;
                                                ?></td>
                                            <td><?php
                                                echo $camp14 = $camp11 - $camp13;
                                                $total14 += $camp14;
                                                ?></td>
                                            <td><?php echo $objVariabile->pi_mec; ?></td>
                                            <td><?php
                                                echo $camp16 = $camp14 * $objVariabile->pi_mec;
                                                $total+=$camp16;
                                                ?></td>
                                        </tr>
                                        <?php
                                        $j++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="11">TOTAL SLOT MACHINES</td>
                                        <td><?php echo isset($total11) ? $total11 : 0; ?></td>
                                        <td>-</td>
                                        <td><?php echo isset($total13) ? $total13 : 0; ?></td>
                                        <td><?php echo isset($total14) ? $total14 : 0; ?></td>
                                        <td></td>
                                        <td><?php echo isset($total) ? $total : 0; ?></td>
                                    </tr>
                                </table>
                                <div class="row">
                                    <div class="col-md-2">
                                        <p>Intocmit:</p>
                                        <p>Numele si prenumele:</p>
                                        <p>Functia:</p>
                                        <p>Semnatura</p>
                                    </div>
                                    <div class="col-md-10">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $("#print").printPage({
                                    url: "printIframe.php?id=<?php echo $idLocatie; ?>&zi=<?php echo $zi; ?>&luna=<?php echo $luna; ?>&an=<?php echo $an; ?>",
                                    attr: "href",
                                    message: "Your document is being created"
                                });
                            });
                        </script>
                        <input type="button" id="print" onclick="" class="btn btn-primary btn-md" value="Click Pentru a printa tabelul!" />
                    </div>
                </div>
            </div>    
        </div>
    </body>
</html>