<?php
error_reporting(0);
require_once "../autoloader.php";
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
require_once('../includes/class.db.php');
$appSettings = new SessionClass();
$page = new PageClass();
$database = new DataConnection();
$datab = new datab('', 'root', '', 'localhost', 'brunersrl', array());
$aparateMapper = new AparateMapper($database, $appSettings);
$macPicMapper = new MacPicMapper($database, $appSettings);
$locatiiMapper = new LocatiiMaper($database, $appSettings);

$serie = isset($_GET['seria']) ? $_GET['seria'] : 0;


$aparat = $aparateMapper->getAparatBySerie($serie);
$locatie = $locatiiMapper->getLocatie($aparat->getIdLocatie());


$numeCamp = [];
/**
 * Daca s-a ales seria unui aparat se verifica bitii de comanda
 */
if (isset($_GET['seria'])) {
    $get = $db->sanitizePost($_GET);
    /**
     * Se preia aparatul
     */
    $aparat = $aparateMapper->getAparatBySerie($_GET['seria']);
    if ($aparat->getStareaparate()->getBitiComanda() != 0) {
        $inAsteptate = $page->getBinariFromDecimal($aparat->getStareaparate()->getBitiComanda());
        /**
         * Se creaza un array cu valorile ce sunt in asteptare (nu au apucat sa fie trimise la aparat)
         */
        foreach ($inAsteptate as $key => $value) {
            if ($value == 1) {
                $semnificatie = $page->getSemnificatieBiti($key);
                $numeCamp[$semnificatie['denumireCamp']] = $semnificatie;
            }
        }
    }
}
/**
 * Se sanitizeaza formularul
 */
if (!empty($_POST['submit'])) {
    $post = $db->sanitizePost($_POST);
}
/**
 * Daca se apasa butonul de audit se modifica direct in baza de date
 */

/**
 * Daca se apasa butonul de record se trimite direct in baza de date
 */
if (!empty($_POST['record'])) {
    $post = $db->sanitizePost($_POST);
    $biti = $page->getBinariFromDecimal($aparat->getStareaparate()->getBitiComanda());
    $biti[12] = 1;
    $bitiComanda = $macPicMapper->getStringComanda($biti);
    $query = "UPDATE {$this->getDatabase()}.stareaparate SET bitiComanda=$bitiComanda, stareRetur='1' WHERE idAparat='{$aparat->getIdAparat()}'";
    if ($db->query($query)) {
        echo $page->printDialog('success', 'Indexi trimisi la record cu success!');
    } else {
        echo $page->printDialog('danger', $db->error);
    }
}
if (isset($_POST['submit'])) {
    if (isset($_POST['configurareAparat'])) {
        if (isset($_GET['seria'])) {
            $post = $db->sanitizePost($_POST);
            $get = $db->sanitizePost($_GET);
            $item = $aparateMapper->getAparatBySerie($_GET['seria']);
            $bitiComanda = [];
            $counter = 1;
            $nrPost = count($post);
            $query = "UPDATE {$db->getDatabase()}.stareaparate SET ";
            foreach ($post as $key => $value) {
                $counter++;
                if (strpos($key, 'bit') !== FALSE) {
                    $nrBit = substr($key, 3, 2);
                    $bitul = $page->getSemnificatieBiti($nrBit);
                    if ($value != '') {
                        if ($nrBit == 31 AND $item->getIdAparat() == $value) {
                            $bitiComanda[$nrBit] = 1;
                        } elseif ($nrBit != 31) {
                            $bitiComanda[$nrBit] = 1;
                        }

                        if ($bitul['bazaDate'] != 'skip') {
                            $query .= " {$bitul['bazaDate']}='{$value}' ";
                            $query .= ',';
                        }

                    } else {
                        $bitiComanda[$nrBit] = 0;
                    }
                }
            }
            $bitiComanda['11'] = 1;
            $stringComanda = $macPicMapper->getStringComanda($bitiComanda);
            $query .= " stareRetur='1', bitiComanda='{$stringComanda}' ";
            $query .= " WHERE idAparat='{$aparat->getIdAparat()}'";
            print_r($bitiComanda);
            echo $query;
            if ($db->query($query)) {
                echo $page->printDialog('success', 'Aparatul a fost configurat cu success!');
                $datab->logsInsertRow_fromOld($_SESSION['username'], 'UPDATE', 'stareaparate', $query);
            } else {
                echo $page->printDialog('danger', "Aparatul nu a putut fi configurat! Va rugam sa atentionati personalul tehnic!" . $db->error);
            }
            $bitiComanda = $macPicMapper->getStringComanda($bitiComanda);
        }
    } elseif ($_POST['configurareLot']) {
        $counter = 1;
        $nrPost = count($_POST);
        $bitiComanda = $page->instantiazaBitiComanda();
        $query = "UPDATE {$db->getDatabase()}.stareaparate ";
        /**
         * Se verifica ce tip de configurare in masa este prioritar
         * Ordinea este de la stanga (cel cu prioritate maxima) la dreapta (cel cu prioritate minima)  :
         * Operator -> Responsabil -> Locatie
         */
        if ($_POST['IdOperator'] != '') {
            $query .= " INNER JOIN aparate ON aparate.idAparat=stareaparate.idAparat INNER JOIN locatii ON locatii.idlocatie = aparate.idlocatie SET ";
        } elseif ($_POST['IdOperator'] == '' AND $_POST['IdResponsabil'] != '') {
            $query .= " INNER JOIN aparate ON aparate.idAparat=stareaparate.idAparat INNER JOIN locatii ON locatii.idlocatie = aparate.idlocatie SET ";
        } elseif ($_POST['IdOperator'] == '' AND $_POST['IdResponsabil'] == '' AND $_POST['IdLocatie'] != '') {
            $query .= " INNER JOIN aparate ON aparate.idAparat = stareaparate.idAparat SET ";
        } else {
            echo $page->printDialog('danger', 'Detaliile pentru configurare in masa nu sunt corecte!');
        }
        foreach ($_POST as $key => $value) {
            $counter++;
            if (strpos($key, 'bit') !== FALSE) {
                $nrBit = substr($key, 3, 2);
                $bitul = $page->getSemnificatieBiti($nrBit);
                /**
                 * Verificam ca bitii de configurare sa nu vizeze biti caracteristici unui singur aparat
                 */
                if ($nrBit != 31 AND $nrBit != 30 AND $nrBit != 29 AND $nrBit != 28 AND $value != '') {
                    $bitiComanda[$nrBit] = 1;
                    $query .= " {$bitul['bazaDate']} = {$value}, ";
                }
            }
        }
        $bitiComanda['11'] = 1;
        $stringComanda = $macPicMapper->getStringComanda($bitiComanda);
        $query .= " bitiComanda={$stringComanda}, stareRetur=1 ";
        /**
         * Se verifica ce tip de configurare in masa este prioritar
         * Ordinea este de la stanga (cel cu prioritate maxima) la dreapta (cel cu prioritate minima)  :
         * Operator -> Responsabil -> Locatie
         */
        if ($_POST['IdOperator'] != '') {
            $query .= " WHERE locatii.idOperator={$_POST['IdOperator']}";
        } elseif ($_POST['IdOperator'] == '' AND $_POST['IdResponsabil'] != '') {
            $query .= " WHERE locatii.idresp={$_POST['IdResponsabil']} ";
        } elseif ($_POST['IdOperator'] == '' AND $_POST['IdResponsabil'] == '' AND $_POST['IdLocatie'] != '') {
            $query .= " WHERE aparate.idLocatie={$_POST['IdLocatie']}";
        } else {
            echo $page->printDialog('danger', 'Detaliile pentru configurare in masa nu sunt corecte!');
            $query = '';
        }
        if ($query !== '') {
            if ($db->query($query)) {
                echo $page->printDialog('success', 'Setarile pentru configurarea in masa au fost salvate!');
            } else {
                echo $page->printDialog('danger', "Setarile pentru configurarea in masa nu au putut fi salvate!");
            }
        }
    } else {
        echo $page->printDialog('danger', 'Va rugam sa reviuiti formularul, datele introduse sunt incorecte!');
    }
}

?>
<!DOCTYPE html>

<head>
    <title>SC AMPERA SRL</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" type="text/css">
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css"/>
    <meta charset="UTF-8"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type=""></script>
    <script src="../js/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript" src="audit.js"></script>
</head>
<html>
<body><br/>
<!--<div class="alert alert-danger"><strong>Atentie!!</strong> Se lucreaza</div>-->
<a class="btn btn-primary btn-sm" href="<?php echo DOMAIN; ?>/main.php">Inapoi la Aplicatie</a>

<div class="clearfix"></div>
<div align="right" style="float:left; display : inline-block">
    <form name="CfgAparat" method="POST" id="configForm" style="display: inline-block;">
        <div id="content_cfg">
            <div id="Tbl_02">
                <table style='position: relative'>
                    <colgroup>
                        <col style="width:30%">
                        <col style="width:25%">
                        <col style="width:45%">
                    </colgroup>
                    <tr>
                        <th>Tip Configurare</th>
                        <td><input class="left stare" type="checkbox" name="configurareAparat" id="configurareAparat"
                                   checked><label for="configurareAparat">Configureaza Aparat</label></td>
                        <td>
                            <input class="left stare" type="checkbox" name="configurareLot" id="configurareLot"><br />
                            <label
                                for="configurareLot">Configureaza Lot</label>
                            <?php
                                if (isset($_GET[seria])) {
                                    // error_reporting(E_ALL);
                                    $dateStare = $datab->getRows('stareaparate', 'verSoft, macPic', 'WHERE idAparat=?', $array=array($aparat->getIdAparat()));
                                }
                            ?>
                            Vers. Soft: <strong><?php echo $dateStare[0][verSoft]; ?></strong> <br />
                            MAC: <strong><?php echo $dateStare[0][macPic]; ?></strong>
                        </td>
                    </tr>
                    <?php
                    if ($locatie->getIdlocatie() !== NULL) {
                        ?>
                        <tr>
                            <th>Nume locatie :</th>
                            <td><strong><?php echo $locatie->getDenumire() ?></strong></td>
                            <td></td>
                        </tr>
                        <?php
                    } ?>
                    <input type='hidden' id="idAparat" value="<?php echo  $aparat->getIdAparat(); ?>">
                    <tr class="proprietate-aparat">
                        <th>Serie Aparat :</th>
                        <td><label><input name="serieAparat" type="text"
                                          value="<?php echo (isset($get['seria'])) ? $aparat->getSeria() : ''; ?>"
                                          required
                                          id="serieAparat"/></label>
                            <script type="text/javascript">
                                $(function () {
                                    var seriiUnice = [
                                        <?php
                                        $aparate = $aparateMapper->getDistinctSerii();
                                        foreach($aparate as $aparata){
                                            echo '"'.$aparata.'",';
                                        }
                                        ?>
                                    ];
                                    $("#serieAparat").autocomplete({
                                        source: seriiUnice
                                    });
                                });
                            </script>
                        </td>
                        <td></td>
                    </tr>
                   <!--  <tr class="proprietate-lot">
                        <th>ID Operator :</th>
                        <td><label><input name="IdOperator" type="text" value="<?php echo (isset($get['seria'])) ? $locatie->getIdOperator() : ''; ?>"/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $locatie->getIdOperator() : ''; ?></td>
                    </tr>
                    <tr class="proprietate-lot">
                        <th>Id Responsabil</th>
                        <td><label><input type="text" name="IdResponsabil" value="<?php echo isset($get['seria']) ? $locatie->getIdresp() : ''; ?>"/></label></td>
                        <td><?php echo isset($get['seria']) ? $locatie->getIdresp() : ''; ?></td>
                    </tr>
                    <tr class="proprietate-lot">
                        <th>ID Locatie :</th>
                        <td><label><input name="IdLocatie" type="text" value="<?php echo (isset($get['seria'])) ? $aparat->getIdLocatie() : ''; ?>"/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $aparat->getIdLocatie() : ''; ?></td>
                    </tr> -->
                    <tr class="proprietate-aparat">
                        <th>ID Aparat :</th>
                        <td><label><input <?php if (isset($numeCamp['bit31'])) {
                                    echo "class='text'";
                                } ?> name="bit31" type="text"
                                     value="<?php echo isset($_GET['seria']) ? $aparat->getIdAparat() : ""; ?>"
                                     required/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $aparat->getIdAparat() : ''; ?></td>
                    </tr>
                    <tr class="proprietate-aparat">
                        <th>Contor Mecanic IN:</th>
                        <td><label><input <?php if (isset($numeCamp['bit30'])) {
                                    echo "class='text'";
                                } ?> name="bit30" type="text" value="<?php if (isset($numeCamp['bit30'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit30']['bazaDate'];
                                } ?>"/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $aparat->getStareaparate()->getLastIdxInM() : ''; ?></td>
                    </tr>
                    <tr class="proprietate-aparat">
                        <th>Contor Mecanic OUT:</th>
                        <td><label><input <?php if (isset($numeCamp['bit29'])) {
                                    echo "class='text'";
                                } ?> name="bit29" type="text" value="<?php if (isset($numeCamp['bit29'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit29']['bazaDate'];
                                } ?>"/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $aparat->getStareaparate()->getLastIdxOutM() : ''; ?></td>
                    </tr>
                    <tr class="proprietate-aparat">
                        <th>Contor Mecanic TotalBet:</th>
                        <td><label><input <?php if (isset($numeCamp['bit28'])) {
                                    echo "class='text'";
                                } ?> name="bit28" type="text" value="<?php if (isset($numeCamp['bit28'])) {
                                    echo $aparat->$numeCamp['bit28']['bazaDate'];
                                } ?>"/></label></td>
                        <td><?php echo (isset($get['seria'])) ? $aparat->getStareaparate()->getLastIdxBetM() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Trimite daca se modifica idxIn</th>
                        <td><label><input class="<?php if (isset($numeCamp['bit27'])) {
                                    echo "checkbox";
                                } ?> left" type="checkbox" name="bit27" <?php if (isset($numeCamp['bit27'])) {
                                    echo "checked";
                                } ?> checked/>Se modifica
                                idxIn</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Trimite daca se modifica idxOut</th>
                        <td><label><input class="left" type="checkbox"
                                          name="bit26" <?php if (isset($numeCamp['bit26'])) {
                                    echo "checked";
                                } ?> checked/>Se modifica
                                idxOut</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Trimite daca se modifica idxBet</th>
                        <td><label><input <?php if (isset($numeCamp['bit25'])) {
                                    echo "class='checkbox'";
                                } ?> type="checkbox" class="left" name="bit25" <?php if (isset($numeCamp['bit25'])) {
                                    echo "checked";
                                } ?>/>Se modifica
                                idxBet</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Contor Mecanic Timp Off :</th>
                        <td><label><input <?php if (isset($numeCamp['bit23'])) {
                                    echo "class='text'";
                                } ?> name="bit23" type="text" value="<?php if (isset($numeCamp['bit23'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit23']['bazaDate'];
                                } else {
                                    echo 3;
                                } ?>"/></label></td>
                        <td>[0..5
                            Secunde] <?php echo isset($get['seria']) ? "Curent: " . $aparat->getStareaparate()->getTimpOff() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Durata Pachet 1:</th>
                        <td><label><input <?php if (isset($numeCamp['bit22'])) {
                                    echo "class='text'";
                                } ?> name="bit22" type="text" value="<?php if (isset($numeCamp['bit22'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit22']['bazaDate'];
                                } else {
                                    echo 600;
                                } ?>"/></label></td>
                        <td>[Secunde]
                            0=Dezactivat <?php echo isset($get['seria']) ? "Curent: " . $aparat->getStareaparate()->getTimpPachet1() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Durata Pachet 2:</th>
                        <td><label><input <?php if (isset($numeCamp['bit21'])) {
                                    echo "class='text'";
                                } ?> name="bit21" type="text" value="3"/></label></td>
                        <td><?php echo isset($get['seria']) ? "Curent: " . $aparat->getStareaparate()->getTimpPachet2() : '' ?></td>
                    </tr>
                    <tr>
                        <th>Contor Electronic (SAS) :</th>
                        <td><label><input <?php if (isset($numeCamp['bit24'])) {
                                    echo "class='checkbox'";
                                } ?> class="left" type="checkbox" name="bit24" <?php if (isset($numeCamp['bit24'])) {
                                    echo "checked";
                                } ?>/>Cind se
                                Modifica</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Porneste Aparat:</th>
                        <td>
                            <label><input type="checkbox" <?php if (isset($numeCamp['bit14'])) {
                                    echo "class='checkbox'";
                                } ?> name="bit14" class="left" <?php if (isset($numeCamp['bit14'])) {
                                    echo "checked";
                                } ?>/>Porneste
                                / Opreste Aparat</label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Stare Citiror de bani</th>
                        <td><label><input <?php if (isset($numeCamp['activeazaCititor'])) {
                                    echo "class='checkbox'";
                                } ?> class="left" type="checkbox" name="activeazaCititor"/>Activeaza
                                / Dezactiveaza Cititor Bani</label></td>
                        <td></td>
                    </tr>
                    <tr class="proprietate-aparat">
                        <!-- <th>Audit</th> -->
                        <th></th>
                        <td><input type="submit" name="audit" value="Efectueaza Audit" id="audit"/>
                            <div id="timp_audit"></div>
                            
                        </td>
                        <td></td>
                    </tr>
                    <tr class="proprietate-aparat">
                        <th>Record</th>
                        <td><input type="submit" name="record" value="Trimite la Record" id="recordButton"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="3" align="center">Configurare Server</th>
                    </tr>
                    <tr>
                        <th>WebServer :</th>
                        <td><label><input name="bit20" class="<?php if (isset($numeCamp['bit20'])) {
                                    echo 'text';
                                } ?> disabled" type="text" value="<?php if (isset($numeCamp['bit20'])) {
                                    echo $aparat->$numeCamp['bit20']['bazaDate'];
                                } else {
                                    echo "http://red77.ro:43568";
                                } ?>" disabled/></label></td>
                        <td>(Max. 64
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getAdrPachet1() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Adresa Pachet 2 :</th>
                        <td><label><input type="text" class="<?php if (isset($numeCamp['bit19'])) {
                                    echo 'text';
                                } ?> disabled" name="bit19" value="<?php if (isset($numeCamp['bit19'])) {
                                    echo $aparat->$numeCamp['bit19']['bazaDate'];
                                } else {
                                    echo "http://red77.ro:43562";
                                } ?>" disabled/></label></td>
                        <td>(Max. 64
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getAdrPachet2() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Adresa Pachet 3 :</th>
                        <td><label><input type="text" class="<?php if (isset($numeCamp['bit18'])) {
                                    echo 'text';
                                } ?> disabled" name="bit18" value="<?php if (isset($numeCamp['bit18'])) {
                                    echo $aparat->$numeCamp['bit18']['bazaDate'];
                                } else {
                                    echo "http://red77.ro:43563";
                                } ?>" disabled/></label></td>
                        <td>(Max. 64
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getAdrPachet3() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Host Name :</th>
                        <td><label><input class="InHost  <?php if (isset($numeCamp['bit17'])) {
                                    echo "text";
                                } ?> " name="bit17" type="text" value="<?php if (isset($numeCamp['bit17'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit17']['bazaDate'];
                                } else {
                                    echo "ampera";
                                } ?>"/></label></td>
                        <td>(Max. 15
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getHostNamePic() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>User Name :</th>
                        <td><label><input class="InHost  <?php if (isset($numeCamp['bit16'])) {
                                    echo "text";
                                } ?> " name="bit16" type="text" value="<?php if (isset($numeCamp['bit16'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit16']['bazaDate'];
                                } else {
                                    echo "admin";
                                } ?>"/></label></td>
                        <td>(Max. 15
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getUserPic() : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Password :</th>
                        <td><label><input class="InHost  <?php if (isset($numeCamp['bit15'])) {
                                    echo "text";
                                } ?> " name="bit15" type="text" value="<?php if (isset($numeCamp['bit15'])) {
                                    echo $aparat->getStareaparate()->$numeCamp['bit15']['bazaDate'];
                                } else {
                                    echo "ampera";
                                } ?>"/></label></td>
                        <td>(Max. 15
                            Caractere) <?php echo isset($get['seria']) ? $aparat->getStareaparate()->getPassPic() : ''; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><input name="submit" type="submit" value="Salvare Configurare" class="BtnCfg"
                                               id="saveButton"/>
                            <?php
                                if (isset($_GET[seria])) {
	                            	$idAparatVechi = $datab->getRows('stareaparate', 'idAparat, idApRetur', 'WHERE idApRetur=? OR idAparat = ? ORDER BY idAparat DESC', array($aparat->getIdAparat(), $aparat->getIdAparat()));
                                    // print_r($idAparatVechi);
	                            	if ($idAparatVechi[1][idApRetur] != $idAparatVechi[0][idAparat]) {
	                        ?>
			                            <input type="button" name="updateIpRetur" value="Update IpRetur" id="updateIpRetur"/>
			                            <div id="istoricAparat">
                            <?php	
                                    	   echo $idAparatVechi[1][idAparat].' -> '.$aparat->getIdAparat();
                            ?>
                                        </div>
                            <?php
                                   	}
                                }
                            ?>
                            
                        </td>
                        
                    </tr>
                </table>
            </div>
        </div>
    </form>
    <form method="POST" id="formAudit">
        <input type="hidden" name="audit" value="1"/>
    </form>
    <form method="POST" id="formRecord">
        <input type="hidden" name="record" value="1"/>
    </form>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#recordButton').click(function (event) {
                event.preventDefault();
                $('#formRecord').submit();
            });

        });
        $(document).on('change', '#configurareAparat', function () {
            $('.stare').not(this).prop('checked', false);
            if ($(this).is(':checked')) {
                $('.proprietate-aparat').show();
                $('.proprietate-lot').hide();
                $('input[name="serieAparat"]').prop('required', true);
                $('input[name="bit31"]').prop('required', true);
            } else {
                $('.proprietate-aparat').hide();
                $('.proprietate-lot').show();
                $('input[name="serieAparat"]').attr('required', false);
                $('input[name="bit31"]').attr('required', false);
            }
        });
        $(document).on('change', '#configurareLot', function () {
            $('.stare').not(this).prop('checked', false);
            if ($(this).is(':checked')) {
                $('.proprietate-aparat').hide();
                $('.proprietate-lot').show();
                $('input[name="serieAparat"]').attr('required', false);
                $('input[name="bit31"]').attr('required', false);
            } else {
                $('.proprietate-aparat').show();
                $('.proprietate-lot').hide();
                $('input[name="serieAparat"]').prop('required', true);
                $('input[name="bit31"]').prop('required', true);
            }
        });
        $(document).on('autocompletechange', '#serieAparat', function () {

            if (($(this).val().length == 7) || ($(this).val().length == 6)) {
                
                window.location.href = "?seria=" + $(this).val();
            }
        });

    </script>
</div>
<div id="stareAparatBazaDeDate" class="left" style="display: inline-block; float : right;">
    <div id="response"></div>
    <div id="tabel">
        <form name="CfgAparat" method="POST" id="configForm" style="display: inline-block; float:left">
            <div id="content_cfg" class="formAudit">
                <div id="Tbl_02" style='position: relative'>
                    <table>
                        <colgroup>
                            <col style="width:50%">
                            <col style="width:50%">
                        </colgroup>
                        <tr>
                            <th>Stare pachet</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Data Ultimei reincarcari</th>
                            <td id="audit-data"><?php echo date("Y-m-d H:i:s") ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Nume locatie :</th>
                            <td id="audit-locatie"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>MAC :</th>
                            <td id="audit-max"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Ver. Soft :</th>
                            <td id="audit-soft"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-aparat">
                            <th>Serie Aparat :</th>
                            <td id="audit-serie"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-lot">
                            <th>ID Operator :</th>
                            <td id="audit-op"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-lot">
                            <th>Id Responsabil</th>
                            <td id="audit-resp"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-lot">
                            <th>ID Locatie :</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-aparat">
                            <th>ID Aparat :</th>
                            <td id="audit-aparat"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-aparat">
                            <th>Contor Mecanic IN:</th>
                            <td id="audit-in"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-aparat">
                            <th>Contor Mecanic OUT:</th>
                            <td id="audit-out"></td>
                            <td></td>
                        </tr>
                        <tr class="proprietate-aparat">
                            <th>Contor Mecanic TotalBet:</th>
                            <td id="audit-total"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Contor Mecanic Timp Off :</th>
                            <td id="audit-off"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Durata Pachet 1:</th>
                            <td id="audit-pachet1"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Durata Pachet 2:</th>
                            <td id="audit-pachet2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Contor Electronic (SAS) :</th>
                            <td id="audit-sas"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="3" align="center">Configurare Server</th>
                        </tr>
                        <tr>
                            <th>WebServer :</th>
                            <td id="audit-server"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Adresa Pachet 2 :</th>
                            <td id="audit-adr2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Adresa Pachet 3 :</th>
                            <td id="audit-adr3"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Host Name :</th>
                            <td id="audit-host"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>User Name :</th>
                            <td id="audit-user"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Password :</th>
                            <td id="audit-pass"></td>
                            <td></td>
                        </tr>
                        <div class="loading"><img src="../css/AjaxLoader.gif" /></div>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
<style type="text/css">
   body {
        font-family: Verdana, Arial, sans-serif !important;
        margin: 0px auto;
        background: #CCC;
        font-size: 16px !important;
        line-height: 17px !important;
    }
    #Tbl_02 tr td, #Tbl_02 tr th {
        padding: 2px 4px;
    }
    #audit {
    	position: absolute;
    	width: 145px;
    	top: 108px;
		right: 10px;
    }
    #saveButton {
    	position: absolute;
		width: 157px;
		height: 25px;
		top: 138px;
		right: 10px;
    }
    #timp_audit {
        position: absolute;
        right: 175px;
        top: 110px;
    }
    #updateIpRetur {
        position: absolute;
        width: 145px;
        top: 166px;
        right: 10px;
    }
    #istoricAparat {
    	position: absolute;
		top: 201px;
		right: 13px;
    }
</style>
<div class="clearfix"></div>
<div id="footer">Copyright &copy; 2015 SC AMPERA SRL</div>
</body>
</html>