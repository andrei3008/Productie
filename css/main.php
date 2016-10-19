<?php
require_once('uploadFile.php');
require_once('includes/dbFull.php');
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
if (isset($_GET['id_pers'])) {
    $id_persoana = $_GET['id_pers'];
} else {
    $id_persoana = 1;
}
if (isset($_GET['op'])) {
    $_SESSION['operator'] = $_GET['op'];
}
if ($_SESSION['grad'] != 0) {
    $id_locatie = $_SESSION['idLocatie'];
}
$page = new PageClass();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pagina Principala</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<?php
if ($_SESSION['grad'] != 0) {
    ?>
    <div class="container-fluid">
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Va rugam sa va modificati parola!</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="formular-parola">
                            <?php
                            $user_info = $db->verifyUser($_SESSION['username'], NULL);
                            if (isset($_POST['form-name']) AND $_POST['form-name'] == 'pass') {

                                $verificare = $page->checkPasswords($user_info->pass, $_POST['oldPass'], $_POST['parolaNoua1'], $_POST['parolaNoua2']);
                                if (!is_bool($verificare)) {
                                    echo printError($verificare);
                                } elseif (!$db->setNewUserPassword($user_info->idpers, $_POST['parolaNoua1'])) {
                                    echo printDialog('danger', 'Nu s-a putut updata utilizatorul!');
                                } else {
                                    echo $page->printDialog('success', 'User modificat cu success! Va multumim!');
                                    header('location:index.php');
                                }
                            }
                            ?>
                            <fieldset>
                                <label for="oldPass">Parola veche</label>
                                <input type="password" name="oldPass" class="form-control"/>
                            </fieldset>
                            <fieldset>
                                <label for="parolaNoua1">Parola noua</label>
                                <input type="password" name="parolaNoua1" class="form-control"/>
                            </fieldset>
                            <fieldset>
                                <label for="parolaNoua2">Reintroduceti parola</label>
                                <input type="password" name="parolaNoua2" class="form-control"/>
                            </fieldset>
                            <input type="hidden" name="form-name" value="pass"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="salveazaParola"
                                data-user="<?php echo $user_info->idlocatie; ?>">Salveaza Parola
                        </button>
                    </div>
                    <?php
                    if ($_SESSION['flag'] == 0) {
                        ?>
                        <script>
                            $('#myModal').modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });
                            $('#salveazaParola').on('click', function (e) {
                                e.preventDefault();
                                $('#formular-parola').submit();
                            });
                        </script>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="container-fluid">
    <?php require_once 'includes/menu.php'; ?>
    <div class="row">

    </div>
    <div class="spacer"></div>
    <?php if ($_SESSION['grad'] == 0) { ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            $numarAparateAmpera = $db->getUserAparate('1');
            $numarAparateRedlong = $db->getUserAparate('2');
            $personalAmpera = $db->getLocatiiPersonal('1');
            $personalRedLong = $db->getLocatiiPersonal('2');
            $totalLocatiiAmpera = 0;
            $totalLocatiiRedlong = 0;
            $totalLocatiiMare = 0;
            $totalAparateMare = 0;
            $totalAparateAmpera = 0;
            $totalAparateRedlong = 0;
            foreach ($personalAmpera as $objPersonal) {
                $info = '<strong>' . $objPersonal->nume . ' ' . $objPersonal->prenume . "</strong><br/>"
                    . 'Telefon : ' . $objPersonal->telefon . "<br/>"
                    . 'User    : ' . $objPersonal->user . "<br/>"
                    . 'Pass    : ' . $objPersonal->pass;
                ?>
                <div class="col-md-3 height130">
                    <table class="table no-padding">
                        <tr class="ampera-tr" data-op="1" data-pers="<?php echo $objPersonal->idpers; ?>">
                            <td><span
                                    class="glyphicon  glyphicon glyphicon-user"></span> <?php echo $objPersonal->nick ?>
                            </td>
                            <td>A(</td>
                            <td><?php echo $objPersonal->nr_locatii ?>L</td>
                            <td> /</td>
                            <td><?php echo isset($numarAparateAmpera[$objPersonal->nick]->nr_aparate) ? $numarAparateAmpera[$objPersonal->nick]->nr_aparate : 0; ?>
                                A
                            </td>
                            <td>/</td>
                            <td><?php echo $db->getAparateDepozitByResponsabil($objPersonal->idpers, 'depozita'); ?>
                                AD
                            </td>
                            <td>/</td>
                            <td>P(0</td>
                            <td>/</td>
                            <td>0</td>
                            <td>))</td>
                        </tr>
                        <?php
                        $totalLocatiiAmpera += $objPersonal->nr_locatii;
                        $totalLocatiiMare += $objPersonal->nr_locatii;
                        $totalAparateAmpera += isset($numarAparateAmpera[$objPersonal->nick]->nr_aparate) ? $numarAparateAmpera[$objPersonal->nick]->nr_aparate : 0;
                        $totalAparateMare += isset($numarAparateAmpera[$objPersonal->nick]->nr_aparate) ? $numarAparateAmpera[$objPersonal->nick]->nr_aparate : 0;
                        foreach ($personalRedLong as $objPersonalRedlong) {
                            if (strtolower($objPersonal->nick) == strtolower($objPersonalRedlong->nick)) {
                                $totalLocatiiRedlong += $objPersonalRedlong->nr_locatii;
                                $totalLocatiiMare += $objPersonalRedlong->nr_locatii;
                                $totalAparateRedlong += isset($numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate) ? $numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate : 0;
                                $totalAparateMare += isset($numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate) ? $numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate : 0;
                                ?>
                                <tr class="redlong-tr" data-op="2" data-pers="<?php echo $objPersonal->idpers; ?>">
                                    <td></td>
                                    <td>R(</td>
                                    <td><?php echo $objPersonalRedlong->nr_locatii; ?>L</td>
                                    <td> /</td>
                                    <td><?php echo isset($numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate) ? $numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate : 0; ?>
                                        A
                                    </td>
                                    <td>/</td>
                                    <td><?php echo $db->getAparateDepozitByResponsabil($objPersonal->idpers, 'depozitr'); ?>
                                        AD
                                    </td>
                                    <td>/</td>
                                    <td>P(0</td>
                                    <td>/</td>
                                    <td>0</td>
                                    <td>))</td>
                                </tr>
                                <tr class="total-tr" data-op="" data-pers='<?php echo $objPersonal->idpers; ?>'>
                                    <td></td>
                                    <td>T(</td>
                                    <td><?php echo($objPersonalRedlong->nr_locatii + $objPersonal->nr_locatii); ?>
                                        L
                                    </td>
                                    <td> /</td>
                                    <td><?php
                                        $totalUber = 0;
                                        $totalUber += isset($numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate) ? $numarAparateRedlong[$objPersonalRedlong->nick]->nr_aparate : 0;
                                        $totalUber += isset($numarAparateAmpera[$objPersonal->nick]->nr_aparate) ? $numarAparateAmpera[$objPersonal->nick]->nr_aparate : 0;
                                        echo $totalUber;
                                        ?>A
                                    </td>
                                    <td>/</td>
                                    <td><?php echo $db->getAparateDepozitByResponsabil($objPersonal->idpers, 'depozit'); ?>
                                        AD
                                    </td>
                                    <td>/</td>
                                    <td>P(0</td>
                                    <td>/</td>
                                    <td>0</td>
                                    <td>))</td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="11"><a href='#' class='btn btn-sm btn-primary'>Test1</a><a href='#'
                                                                                                    class='btn btn-sm btn-primary'>Test1</a><a
                                    href='#' class='btn btn-sm btn-primary'>Test1</a></td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
            <div class="col-md-3 height130">
                <table class="table no-padding">
                    <tr>
                        <td><span class="glyphicon  glyphicon glyphicon-user"></span> Total</td>
                        <td>A(</td>
                        <td><?php echo $totalLocatiiAmpera; ?>L</td>
                        <td> /</td>
                        <td><?php echo $totalAparateAmpera; ?> A</td>
                        <td>/</td>
                        <td><?php echo $db->getAparateDepozitByResponsabil(null, 'depozita'); ?> AD</td>
                        <td>/</td>
                        <td>P(0</td>
                        <td>/</td>
                        <td>0</td>
                        <td>))</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>R(</td>
                        <td><?php echo $totalLocatiiRedlong; ?>L</td>
                        <td> /</td>
                        <td><?php echo $totalAparateRedlong; ?> A</td>
                        <td>/</td>
                        <td><?php echo $db->getAparateDepozitByResponsabil(NULL, 'depozitr'); ?> AD</td>
                        <td>/</td>
                        <td>P(0</td>
                        <td>/</td>
                        <td>0</td>
                        <td>))</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>T(</td>
                        <td><?php echo $totalLocatiiMare; ?>L</td>
                        <td> /</td>
                        <td><?php echo $totalAparateMare; ?>A</td>
                        <td>/</td>
                        <td><?php echo $db->getAparateDepozitByResponsabil(null, 'depozit'); ?> AD</td>
                        <td>/</td>
                        <td>P(0</td>
                        <td>/</td>
                        <td>0</td>
                        <td>))</td>
                    </tr>
                    <tr >
                        <td colspan="3">
                            <fieldset style="margin-top: 8px;">
                                <label for="an">Anul</label>
                                <select name="an">
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                </select>
                            </fieldset>
                        </td>
                        <td colspan="9">
                            <fieldset style="margin-top: 8px;">
                                <label for="luna">Luna</label>
                                <select name="luna">
                                    <option value="01">Ian</option>
                                    <option value="02">Feb</option>
                                    <option value="03">Mar</option>
                                    <option value="04">Apr</option>
                                    <option value="05">Mai</option>
                                    <option value="06">Iun</option>
                                    <option value="07">Iul</option>
                                    <option value="08">Aug</option>
                                    <option value="09">Sep</option>
                                    <option value="10">Oct</option>
                                    <option value="11">Noi</option>
                                    <option value="12">Dec</option>
                                </select>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3 height130">
                <table class="table no-padding">
                    <tr>
                        <td><span class="glyphicon  glyphicon glyphicon-user"></span>Links</td>
                        <td colspan="5"><a href="http://86.122.183.194/minister/main.php" target="blank"
                                           class="btn btn-primary btn-sm">Ampera Minister</a></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="5">
                            <form target="blank" action="http://redlong.ro/minister/main.php" target="blank"
                                  method="POST"><input class="btn btn-primary btn-sm" type="submit"
                                                       value="Redlong Minister" name="ampera"/></form>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            </div>
            </td>
            </tr>
            </table>
        </div>
        </ul>
    </div>
</div>
<?php } ?>
<div class="row">
    <?php if ($_SESSION['grad'] == 0 OR $_SESSION['grad'] == 3) { ?>
        <div class="col-md-3 col-sm-3">
            <div class="panel panel-primary" id="locatiiPanel">
                <div class="panel-heading">
                    <strong><span
                            class="glyphicon  glyphicon glyphicon-user"></span><?php echo $personalAmpera[$id_persoana]->nick; ?>
                    </strong>
                    <italic><?php
                        if ($_SESSION['operator'] == 1) {
                            echo 'A' . '(' . $personalAmpera[$id_persoana]->nr_locatii . 'L / ' . $numarAparateAmpera[$personalAmpera[$id_persoana]->nick]->nr_aparate . 'A / ' . $db->getAparateDepozitByResponsabil($personalAmpera[$id_persoana]->idpers, 'depozita') . 'AD ) P(0/0)';
                        } elseif ($_SESSION['operator'] == 2) {
                            echo 'R' . '(' . $personalRedLong[$id_persoana]->nr_locatii . 'L / ' . $numarAparateRedlong[$personalRedLong[$id_persoana]->nick]->nr_aparate . 'A / ' . $db->getAparateDepozitByResponsabil($personalRedLong[$id_persoana]->idpers, 'depozitr') . 'AD ) P(0/0)';
                        } else {
                            echo 'T' . '(' . ($personalAmpera[$id_persoana]->nr_locatii + $personalRedLong[$id_persoana]->nr_locatii) . 'L / ' . ($numarAparateRedlong[$personalRedLong[$id_persoana]->nick]->nr_aparate + $numarAparateAmpera[$personalAmpera[$id_persoana]->nick]->nr_aparate) . 'A / ' . $db->getAparateDepozitByResponsabil($id_persoana, 'depozit') . 'AP ) P(0/0)';
                        } ?></italic>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php
                        $locatii = $db->getLocatii($_SESSION['operator'], $id_persoana);
                        $i = 1;
                        foreach ($locatii as $objLocatii) {
                            if (isset($_GET['id_locatie'])) {
                                $id_locatie = $_GET['id_locatie'];
                            } elseif ($i == 1) {
                                $id_locatie = $objLocatii->idlocatie;
                            }
                            ?>
                            <li class="list-group-item"><a
                                    href="?id_pers=<?php echo $id_persoana; ?>&id_locatie=<?php echo $objLocatii->idlocatie; ?>#main-content"><?php
                                    echo $i . '. ' . $page->maxText($objLocatii->denumire, 22);
                                    $i++;
                                    ?></a><span class="pull-right"><?php echo $objLocatii->nrAparate ?> AP</span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
    <div
        class="col-md-<?php echo ($_SESSION['grad'] == 0 OR $_SESSION['grad'] == 3) ? 9 : 12 ?>  col-sm-<?php echo ($_SESSION['grad'] == 0 OR $_SESSION['grad'] == 3) ? 9 : 12; ?>">
        <?php echo $page->createInput('hidden', 'idLocatie', $id_locatie, 'id="idLocProdInv"'); ?>
        <div class="panel panel-primary" id="mainPanel">
            <?php $objInfoLocatie = $db->getLocationInfo($id_locatie); ?>
            <div
                class="panel-heading"><?php echo '<strong>' . $objInfoLocatie->nickLocatie . '</strong> / ' . $objInfoLocatie->denumire . ' / ' . $objInfoLocatie->adresa ?><span class="pull-right"><strong>IdOp :</strong><?php echo $objInfoLocatie->idOperator; ?> <strong>IdLoc :</strong><?php echo $objInfoLocatie->idlocatie; ?></span></div>
            <div class="panel-body>
                            <?php
            if (!empty($_POST) AND isset($_POST['dosar'])) {
            $result = uploadFile($_FILES, $_POST['dosar'], $_POST);
            if ($result == '') {
            ?>
                                    <div class=" alert alert-success
            " id="dispare">
            <strong>Success!</strong> Fisierul a fost incarcat cu success!
        </div>
        <?php
        } else {
        ?>
        <div class="alert alert-danger" id="dispare">
            <strong>Danger!</strong> <?php echo $result; ?>.
        </div>
        <?php
        }
        }
        ?>
        <?php
        if (!empty($_POST) AND $_POST['form-name'] == 'index') {
        $lastIndex = $db->getLastIndexByLocation($_SESSION['operator'], $id_locatie);
        $i == 1;
        foreach ($_POST as $key => $value) {
            echo (int)$value . '<br/>';
            $parts = explode('_', $key);
            if (count($parts) >= 3) {
                if (isset($_POST['reset_counter_' . $id])) {
                    $value = $_POST{'reset_counter' . $id} . $value;
                } else {
                    intval($value);
                }
                $idAparat = $parts[2];
                $inOut = $parts[1];
                if ($inOut == 'in') {
                    if ($lastIndex[$idAparat]->lastidxInM <= $_POST['input_in_' . $idAparat]) {
                        $error = 0;
                    } else {
                        $error = 1;
                        break;
                    }
                } elseif ($inOut == 'out') {
                    if ($lastIndex[$idAparat]->lastidxOutM <= $_POST['input_out_' . $idAparat]) {
                        $error = 0;
                    } else {
                        $error = 2;
                        break;
                    }
                }
            }
        }

        if ($error == 0) {
            $id = 0;
            foreach ($_POST as $key => $value) {
                $search = explode('_', $key);
                if (count($search) >= 3) {
                    if (isset($_POST['reset_counter_' . $id])) {
                        $value = $_POST{'reset_counter' . $id} . $value;
                    } else {
                        intval($value);
                    }
                    if ($id != $search[2] AND $search[2] != 'indexes') {
                        $id = $search[2];
                        $db->updateStareAparate($_POST['input_in_' . $id], $_POST['input_out_' . $id], $id);
                        if ($db->verificaExistentaIndex($id, $id_locatie) == 1) {
                            $db->updateContori($_POST['input_in_' . $id], $_POST['input_out_' . $id], $id, $id_locatie);
                        } else {
                            $db->insertContori($id, $id_locatie, $_POST['input_in_' . $id], $_POST['input_out_' . $id]);
                        }
                    }
                }
            }
        } else {
        ?>
        <div class="alert alert-danger" id="dispare">
            <strong>Atentie!</strong> Index nu poate fii mai mic decat cel afisat!!!.
        </div>
        <?php
        }
        }
        if (count($locatii) == 2 AND $_SESSION['operator'] == '') {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $.ajax({
                    type: "POST",
                    url: 'ajax/depozit-ajax.php',
                    data: {},
                    success: function (result) {
                        $('#containerDepozit').html(result);
                    }
                });
            })
        </script>
        <?php
        }
        ?>
        <form method="POST" id="containerDepozit">
            <?php $a = new DateTime();
            $b=  new DateTimeZone('Europe/Bucharest');
            $a->setTimezone($b); ?>
            <table class="table-bordered table-striped table-condensed cf col-md-12 tabel-raport">
                <thead>
                <tr>
                    <th class="centered">ID AP</th>
                    <th class="centered">Poz Loc</th>
                    <th class="centered">Seria</th>
                    <th class="centered">Tip Aparat</th>
                    <th class="centered">Total In</th>
                    <th class="centered">Total Out</th>
                    <th class="centered">Data</th>
                    <th class="centered">Inactivitate</th>
                    <th class="centered">Actiuni</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $aparateInfo = $db->getInfoAparate($id_locatie, $id_persoana);
                $j = 1;
                if (count($aparateInfo) > 0) {
                    foreach ($aparateInfo as $objInfoAparate) {
                        ?>
                        <tr>
                            <td><?php echo $objInfoAparate->idAparat ?></td>
                            <td><?php
                                echo $objInfoAparate->pozitieLocatie;
                                $a->format('Y-m-d H:i:s');
                                $datetime2 = date_create($objInfoAparate->ultimaConectare,$b);
                                $interval = date_diff($datetime2, $a);
                                $diferentaOre =  $interval->format('%h');
                                ?><img src="css/images/<?php echo ($diferentaOre > 1) ? 'red_light.png' : 'green_light.png' ?>" style="width: 20px; height : 20px; float:right"/></td>
                            <td><?php echo $objInfoAparate->seria; ?></td>
                            <td><?php echo $objInfoAparate->tip; ?></td>
                            <td><input type="text"
                                       name="input_in_<?php echo $objInfoAparate->idAparat; ?>"
                                       value="<?php echo $page->verifyIndexLength($objInfoAparate->lastidxInM); ?>"
                                       style="width: 60px;"/>
                                <?php
                                if (is_array($page->testReset($objInfoAparate->lastidxInM))) {
                                    $aparatIndex = $page->testReset($objInfoAparate->lastidxInM);
                                    $page->createInput('hidden', 'contor_reset_' . $objInfoAparate->idAparat, $aparatIndex['reset']);
                                }
                                ?></td>
                            <td><input type="text"
                                       name="input_out_<?php echo $objInfoAparate->idAparat ?>"
                                       value="<?php echo $page->verifyIndexLength($objInfoAparate->lastidxOutM); ?>"
                                       style="width: 60px;"/></td>
                            <td><?php echo $objInfoAparate->ultimaConectare; ?></td>
                            <td><?php
                                $diferentaZile =  $interval->format('%a');
                                if($diferentaZile == 0){
                                    echo  $interval->format('%h ore %i min %s sec');
                                }else {
                                    echo $interval->format('%R%a zile');
                                }
                                ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary extra">PVI</a>
                                <a href="#" class="btn btn-sm btn-primary extra">PVA</a>
                                <a href="#" class="btn btn-sm btn-primary"><span
                                        class="glyphicon glyphicon-random"></span></a>
                                <a href="#" class="btn btn-sm btn-primary">D</a>
                                <a href="#" class="btn btn-sm btn-primary extra">VT</a>
                                <?php
                                //$dataMetro = "<form method='POST' enctype='multipart/form-data' ><input type='hidden' name='dosar' value='Metrologii'/><input type='file' class='uploadMetro' name='upload' value='upload'><input type='hidden' name='serie' value='" . $objInfoAparate->seria . "'/></form>";
                                ?>
                                <!--<a href="#" class="btn btn-sm btn-success metro" data-title="Actiuni"  data-placement="left" data-trigger="click" data-html="TRUE" data-content="<?php echo $dataMetro; ?>">Metro</a>-->
                                <a href="ftp://catalin:catag@rodiz.ro/FisierePDF/Metrologii/2015/<?php echo $objInfoAparate->seria ?>.pdf"
                                   target="_blank" class="btn btn-sm btn-primary">Metrologie</a>
                                <?php $dataAutorizatii = "<form method='POST' enctype='multipart/form-data' ><input type='hidden' name='dosar' value='Autorizatii'/><input type='file' class='uploadMetro' name='upload' value='upload'><input type='hidden' name='serie' value='" . $objInfoAparate->seria . "'/></form>"; ?>
                                <a href='#' class='btn btn-sm btn-primary autorizatii'
                                   data-title='Actiuni' data-placement='left' data-trigger='click'
                                   data-html='TURE' data-content="<?php echo $dataAutorizatii; ?>">Autorizatii</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <input type="submit" name="submit" value="submit" style="display: none;"
                   id="submit-me"/>
            <input type="hidden" name="form-name" value="index"/>
        </form>
        <div class="panel panel-primary" style="display : none;" id="extraOptiuni">
            <div class="panel-heading">
                Extra Optiuni
            </div>
            <div class="col-md-6">
                <p class="text-center">Genereaza</p>
                <a href="" class="btn btn-sm btn-primary center-block"><span
                        class="glyphicon glyphicon-download"></span></a>
            </div>
            <div class="col-md-6">
                <p class="text-center">Incarca</p>
                <a href="" class="btn btn-sm btn-primary center-block"><span
                        class="glyphicon glyphicon-upload"></span></a>
            </div>
        </div>
    </div>
    <div class="butoane">
        <a href="raportzilnic.php?id=<?php echo $objInfoLocatie->idlocatie; ?>"
           class="btn btn-primary btn-md">Raport Zilnic</a>
        <a href="raportlunar.php?id=<?php echo $objInfoLocatie->idlocatie; ?>"
           class="btn btn-primary btn-md"/>Raport Lunar</a>
        <a href="#" class="btn btn-primary" id="save-form-submit">Salveaza Index</a>
        <a href="#" class="btn btn-primary" id="inventar">Adauga obiecte in inventar</a>
        <?php
        $footer = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                         <button type=\"button\" class=\"btn btn-primary\" id='salveazaInventar'>Introdu Element</button>";
        $formular = $page->createFieldset('text', 'denumire', 'Denumire Produs', "id='denProdInv' class='form-control'");
        $formular .= $page->createFieldset('text', 'cantitate', 'Cantitate in bucati', "id='canProdInv' class='form-control'");
        $formular .= $page->createFieldset('text', 'stare', 'Stare Produse', "id='stareProdInv' class='form-control'");
        $formular .= $page->createFieldset('text', 'observatii', 'Observatii', "id='observatiiProdInv' class='form-control'");
        echo $page->createModal('inventar-modal', 'Adauga Elemente In Inventar', $formular, $footer); ?>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading" id="infoFirma">Click pentru informatii locatie</div>
        <div class="panel-body" style="display : none;">
            <?php


            ?>
            <table class="table table-responsive">
                <tr>
                    <td><strong>Fond:</strong> <?php echo $objInfoLocatie->fond; ?></td>
                    <td><strong>Incasari noi:</strong> 0</td>
                    <td><strong>Incasari restante:</strong> 0</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Detinator:</strong> <?php echo $objInfoLocatie->denumire; ?>
                    </td>
                    <td><strong>Manager:</strong> <?php echo $objInfoLocatie->manager; ?></td>
                </tr>
                <tr>
                    <td><strong>Regiune:</strong> <?php echo $objInfoLocatie->regiune; ?></td>
                    <td><strong>Localitate:</strong> <?php echo $objInfoLocatie->localitate; ?></td>
                    <td><strong>Adresa:</strong> <?php echo $objInfoLocatie->adresa; ?></td>
                </tr>
                <tr>
                    <td><strong>Persoana
                            Contact: </strong> <?php echo $objInfoLocatie->persContact; ?></td>
                    <td><strong>Telefon:</strong> <?php echo $objInfoLocatie->telefon; ?></td>
                    <td>
                        <fieldset class="form-inline">
                            <strong>Parola Noua : </strong>
                            <input type="password" name="pass" class="form form-control"
                                   id="form-pass"/>
                            <input type="hidden" name="loc" value="<?php echo $id_locatie; ?>"
                                   id="val_loc"/>
                            <input type="submit" value="Schimba" id="schimbaParola"
                                   class="btn btn-md btn-default"/>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading" id="panel-angajati">Click aici pentru a vizualiza
            angajatii.
        </div>
        <div class="panel-body table-responsive" style="display: none;">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Principal</th>
                    <th>Nume</th>
                    <th>Telefon</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <?php $angajati = $db->getAngajati($id_locatie);
                $i = 1;
                foreach ($angajati as $angajat) {
                    if ($i == 1) {
                        $idAngajatActual = $angajat->idpers;
                    }
                    ?>
                    <tr>
                        <td><input type="radio"
                                   name="angajat" <?php echo (strpos($angajat->telefon, 'p') !== FALSE) ? 'checked' : ''; ?>
                                   value="<?php echo $idAngajatActual . "_" . $angajat->idpers ?>"
                                   data-telefon="<?php echo str_replace('p', '', $angajat->telefon); ?>"
                                   class="angajati"></td>
                        <td><?php echo $angajat->nume . ' ' . $angajat->prenume; ?></td>
                        <td>
                            <a href="tel:<?php echo str_replace('p', '', $angajat->telefon); ?>"><?php echo str_replace('p', '', $angajat->telefon); ?></a>
                        </td>
                        <td><?php echo $angajat->email; ?></td>
                    </tr>
                    <?php $i++;
                }

                $footerPersonal = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                         <button type=\"button\" class=\"btn btn-primary\" id='salveazaPersonal'>Introdu Angajat</button>";
                $formularPersonal = $page->createFieldset('text', 'nume', 'Numele de familie', "id='numePersonal' class='form-control'", 1);
                $formularPersonal .= $page->createFieldset('text', 'prenume', 'Prenumele', "id='prenumePersonal' class='form-control'");
                $formularPersonal .= $page->createFieldset('text', 'telefon', 'Telefon', "id='telefonPersonal' class='form-control'", 1);
                $formularPersonal .= $page->createFieldset('email', 'émail', 'Email', "id='emailPersonal' class='form-control'");
                echo $page->createModal('addPersonal', 'Adauga Personal la locatie', $formularPersonal, $footerPersonal); ?>
                </tbody>
            </table>
            <a href="#" class="btn btn-primary" id="adaugaPersonal">Adauga Angajat</a>
        </div>
    </div>
    <div class="panel panel-info" style="margin-top: 5px;">
        <div class="panel-heading" id="inventarPanel">Obiecte pe inventar</div>
        <div class="panel-body table-responsive" style="display: none;">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>Nr. Crt.</td>
                    <td>Nume</td>
                    <td>Cantitate</td>
                    <td>Stare</td>
                    <td>Observatii</td>
                </tr>
                </thead>
                <tbody>
                <?php $inventar = $db->getInventar($id_locatie);
                $k = 1;
                foreach ($inventar as $obiect) { ?>
                    <tr>
                        <td><?php echo $k;
                            $k++; ?></td>
                        <td><?php echo $obiect->denumire; ?></td>
                        <td><?php echo $obiect->cantitate; ?></td>
                        <td><?php echo $obiect->stare ?></td>
                        <td><?php echo $obiect->observatii; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>