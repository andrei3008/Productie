<!DOCTYPE>
<?php
header('location:login.php');
require_once('includes/dbConnect.php');
$invalid_characters = array("$", "%", "#", "<", ">", "|");
if (!isset($_SESSION['operator']) AND !isset($_SESSION['username_redlong'])) {
    header('location:index.php');
}
if (!isset($_GET['id'])) {
    $idLocatie = 2;
} else {
    $idLocatie = str_replace($invalid_characters, "", $_GET['id']);
}
if (isset($_POST['submit'])) {
    $zi = str_replace($invalid_characters, "", $_POST['zi']);
    $ziAnterioara = $zi - 1;
    $luna = str_replace($invalid_characters, "", $_POST['luna']);
    $an = str_replace($invalid_characters, "", $_POST['an']);
} else {
    $zi = date('d', strtotime("-1 days"));
    $ziAnterioara = date('d', strtotime("-2 days"));
    $luna = date('m');
    $an = date('Y');
}
$query = "SELECT "
        . "locatii.idfirma, "
        . "locatii.adresa, "
        . "locatii.regiune, "
        . "firme.denumire,"
        . "personal.nume,"
        . "personal.prenume,"
        . "personal.privilegii "
        . "FROM brunersrl.locatii "
        . "INNER JOIN "
        . "brunersrl.firme O"
        . "N locatii.idfirma=firme.idfirma "
        . "INNER JOIN brunersrl.personal ON locatii.idresp = personal.idpers"
        . " WHERE locatii.idlocatie='" . $idLocatie . "' AND locatii.operator='".$_SESSION['operator']."';";
$stmt = $con->query($query);
;
$infoLocatie = $stmt->fetch_object();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Raport Zilnic <?php echo $infoLocatie->denumire . ' ' . $zi . '-' . $luna . '-' . $an; ?></title>
        <?php require_once('includes/header.php'); ?>
        <style>

        </style>

    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <a href="main.php" class="right btn btn-primary btn-md back">Inapoi</a>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h3>Situatie incasari <?php echo $infoLocatie->denumire . ' ' . $zi . '-' . $luna . '-' . $an; ?></h3>
                            <span class="regiune">Regiune: <?php echo $infoLocatie->regiune ?></span><br/>
                            <span class="regiune">Adresa: <?php echo $infoLocatie->adresa; ?></span>
                            <form method="POST">
                                <label for="zi">Ziua</label>
                                <select name="zi" class="select-black">
                                    <option value="<?php echo $zi ?>"><?php echo $zi; ?></option>
                                    <?php
                                    for ($i = 1; $i < 32; $i++) {
                                        ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <label for="luna">Luna</label>                                
                                <select name="luna" class="select-black">
                                    <option value="<?php echo $luna ?>"><?php echo $luna; ?></option>
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        ?>
                                        <option value="<?php echo ($i < 10) ? '0' . $i : $i; ?>"><?php echo ($i < 10) ? '0' . $i : $i; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <label for="an">Anul</label>
                                <select name="an" class="select-black">
                                    <option value="2015">2015</option>
                                    <option value="2105">2015</option>
                                </select>
                                <button type="submit" name="submit" class="btn btn-md btn-default">Genereaza Raport</button>
                            </form>
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
                                        <p><?php echo $infoLocatie->denumire; ?></p>
                                        <p><?php echo $infoLocatie->adresa; ?></p>                                                                              
                                        <p><?php echo $infoLocatie->regiune; ?></p>
                                        <p><?php echo $zi . '-' . $luna . '-' . $an; ?></p>
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
                                    $queryAparate = "SELECT "
                                            . "aparate.idAparat,"
                                            . "aparate.seria, "
                                            . "aparate.idxInM as inInitial, "
                                            . "aparate.idxOutM as outInitial,"
                                            . "contormecanic" . $an . $luna . ".idxInM,"
                                            . "contormecanic" . $an . $luna . ".idxOutM,"
                                            . "contormecanic" . $an . $luna . ".dtServer"
                                            . " FROM "
                                            . "brunersrl.aparate "
                                            . "INNER JOIN "
                                            . "brunersrl.contormecanic" . $an . $luna
                                            . " ON "
                                            . "aparate.idAparat = contormecanic" . $an . $luna . ".idAparat "
                                            . " INNER JOIN brunersrl.locatii ON locatii.idlocatie = aparate.idLocatie "
                                            . " WHERE"
                                            . " (contormecanic" . $an . $luna . ".dtServer BETWEEN '" . $an . "-" . $luna . "-" . $ziAnterioara . " 00:00:00' AND '" . $an . "-" . $luna . "-" . $zi . " 23:59:59') "
                                            . "AND aparate.idlocatie='" . $idLocatie . "' AND locatii.operator='".$_SESSION['operator']."';";
                                    if ($resultAparate = $con->query($queryAparate)) {
                                        $i = 1;
                                        $j = 0;
                                        while ($objAparate = $resultAparate->fetch_object()) {
                                            $informatii[$j] = $objAparate;
                                            $j++;
                                        }
                                        $j = 1;
                                        $total = 0;
                                        $total11 = 0;
                                        $total14 = 0;
                                        if (isset($informatii)) {
                                            for ($i = 0; $i < sizeof($informatii); $i+=2) {
                                                $queryVariabile = "SELECT variabile.fm_mec, pi_mec, MAX(variabile.data) FROM brunersrl.variabile WHERE variabile.idAparat='".$informatii[$i]->idAparat."' AND variabile.data <= '" . $an . "-" . $luna . "-" . $zi . "'";
                                                $resultVariabile = $con->query($queryVariabile);
                                                $objVariabile = $resultVariabile->fetch_object();
                                                ?>
                                                <tr>
                                                    <td class="centered" id="0"><?php
                                                        echo $j;
                                                        $j++;
                                                        ?></td>
                                                    <td class="centered" class="1"><?php echo $informatii[$i]->seria; ?></td>
                                                    <td class="centered" class="2"><?php echo $camp2 = $informatii[$i]->idxInM + $informatii[$i]->inInitial; ?></td>
                                                    <td class="centered" class="3">0</td>
                                                    <td class="centered" class="4"><?php echo $camp4 = $informatii[$i]->idxOutM + $informatii[$i]->outInitial; ?></td>
                                                    <td class="centered" class="5"><?php echo $camp5 = $informatii[$i + 1]->idxInM + $informatii[$i]->inInitial; ?></td>
                                                    <td class="centered" class="6">0</td>
                                                    <td class="centered" class="7"><?php echo $camp7 = $informatii[$i + 1]->idxOutM + $informatii[$i]->outInitial; ?></td>
                                                    <td class="centered" class="8"><?php echo $objVariabile->fm_mec; ?></td>
                                                    <td class="centered" class="9">0</td>
                                                    <td class="centered" class="10"><?php echo $objVariabile->fm_mec; ?></td>
                                                    <td class="centered" class="11"><?php
                                                        echo $camp11 = ($camp5 - $camp2) * $objVariabile->fm_mec;
                                                        $total11 +=$camp11;
                                                        ?></td>
                                                    <td class="centered" class="12">0</td>
                                                    <td class="centered" class="13"><?php echo $camp13 = ($camp7 - $camp4) * $objVariabile->fm_mec; ?></td>
                                                    <td class="centered" class="14"><?php
                                                        echo $camp14 = $camp11 - $camp13;
                                                        $total14 += $camp14;
                                                        ?></td>
                                                    <td class="centered" class="15"><?php echo $objVariabile->pi_mec; ?></td>
                                                    <td class="centered" class="16"><?php
                                                        echo $camp16 = $camp14 * $objVariabile->pi_mec;
                                                        $total+=$camp16;
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="11">TOTAL SLOT MACHINES</td>
                                        <td class="centered"><?php echo isset($total11) ? $total11 : 0; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td class="centered"><?php echo isset($total14) ? $total14 : 0; ?></td>
                                        <td></td>
                                        <td class="centered"><?php echo isset($total) ? $total : 0; ?></td>
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
                                        <p></p><br/>
                                        <p><?php echo $infoLocatie->prenume ?></p>
                                        <p><?php echo ($infoLocatie->privilegii == NULL) ? 'Nu sunt stabilite privilegii' : $infoLocatie->privilegii; ?></p>
                                        <p>_________________</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
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