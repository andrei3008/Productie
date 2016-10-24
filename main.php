<?php
require_once "autoloader.php";
require_once('uploadFile.php');
require_once "includes/class.db.php";
require_once "includes/class.databFull.php";

$page = new PageClass();
$appSettings = $session = new SessionClass();
$session->exchangeArray($_SESSION);
$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$datab = new datab(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
$databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
$page->checkLogin($session->getUsername(), $session->getOperator());
$dbScrie = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
$get = $db->sanitizePost($_GET);
if (isset($_GET['id_pers'])) {
    $id_persoana = $_GET['id_pers'];
} else {
    $id_persoana = $session->getIdresp();
}
if (isset($_GET['op'])) {
    $session->setOperator($_GET['op']);
}
if ($session->getGrad() != 0) {
    $id_locatie = $session->getIdLocatie();
}

if (!isset($_GET['luna']) OR !isset($_GET['an'])) {
    $luna = date('n');
    $an = date('Y');
} else {
    $luna = $get['luna'];
    $an = $get['an'];
}
if (isset($_GET['id_locatie'])) {
    $id_locatie = $_GET['id_locatie'];
} else {
    $id_locatie = $session->getIdLocatie();
}
if (isset($_GET['sort'])) {
    $sort = $get['sort'];
} else {
    $sort = 'ASC';
}
$post = $dbScrie->sanitizePost($_POST);
if (isset($post['form-name']) AND $post['form-name'] == 'index') {
    foreach ($post as $key => $value) {
        if ($key != 'form-name' AND $key != 'submit') {
            $bucati = explode('-', $key);
            if ($dbScrie->updatePozitieAparat($bucati[1], $value)) {
                echo $page->printDialog('success', 'Pozitia pentru aparatul cu id ' . $bucati[1] . ' a fost modificata cu succes!');
            }
        }
    }
}
$toateAparatele = $databFull->getStareAparate();
$errori = [];
$a = new DateTime();
$b = new DateTimeZone('Europe/Bucharest');
$a->setTimezone($b);
$a->format('Y-m-d H:i:s');
$aparatePerLocatie = [];
$l = 0;
foreach ($toateAparatele as $aparat) {
    $datetime2 = date_create($aparat->ultimaConectare, $b);
    $interval = date_diff($datetime2, $a);
    /** @var DateTime $diferentaOre */
    $diferentaOre = $interval->format('%h');
    /** @var DateTime $diferentaZile */
    $diferentaZile = $interval->format('%a');
    if ($diferentaZile >= 1) {
        $diferentaOre = $diferentaOre + ($diferentaZile * 24);
        if ($diferentaOre > 99) {
            $diferentaOre = 99;
        }
    }
    /** @var DateTime $diferentaLuni */
    $diferentaLuni = $interval->format('%n');
    /** @var DateTime $diferentaAni */
    $diferentaAni = $interval->format('%y');
    $valoare = $diferentaOre . ',' . $diferentaZile . ',' . $diferentaLuni . ',' . $diferentaAni;
    if ($diferentaOre >= 1 or $diferentaZile >= 1) {
        $error[$aparat->idresp][$aparat->idOperator][$aparat->idLocatie] = 1;
        $aparatePerLocatie[$aparat->idLocatie][$l] = $valoare;
    } else {
        $aparatePerLocatie[$aparat->idLocatie][$l] = $valoare;
    }
    $l++;
}
if (isset($_POST['form-rooter-locatie']) AND $_POST['form-rooter-locatie'] == "form-rooter-locatie") {
    $post = $db->sanitizePost($_POST);
    if ($post['actiune'] == 'insert') {
        for ($i = 1; $i < 4; $i++) {
            if ($db->insertIntoNet($post['loc'], $post['port_' . $i], $i, $post['user_' . $i], $post['parola_' . $i])) {
                echo $page->printDialog('success', 'Salvat cu success');
            } else {
                echo $page->printDialog('danger', 'Eroare la salvare');
            }
        }
    } else {
        foreach ($post as $key => $value) {
            $relevant = explode('_', $key);
            if (isset($relevant[1])) {
                if ($relevant[0] == 'port') {
                    $infoArray[$relevant[1]]['port'] = $value;
                } else if ($relevant[0] == 'user') {
                    $infoArray[$relevant[1]]['user'] = $value;
                } else {
                    $infoArray[$relevant[1]]['pass'] = $value;
                }
            }
        }
        foreach ($infoArray as $key => $value) {
            if ($db->updateIntoNet($key, $value['port'], $value['user'], $value['pass'])) {
                echo $page->printDialog('success', 'Editat cu succes!');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pagina Principala</title>
    <?php 
        require_once('includes/header.php'); 
    ?>
    <script type='text/javascript' src='<?php echo DOMAIN; ?>/js/main-custom.js'></script>
    <?php 
        if ($session->getGrad() != 0 AND $session->getGrad() != 3) {
    ?>

    <?php
        }
    ?>
    <?php
        /*------------------------------------------------
         *  HEADER RAPOARTE - SILVIU - 18.10.2016
         *----------------------------------------------*/
    ?>
        <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN; ?>/includes/rapoarte/rapoarte.css">
        <script src="<?php echo DOMAIN; ?>/includes/rapoarte/custom-situatie.js"></script>
    <?php
        /*------------------------------------------------
         *  END HEADER RAPOARTE
         *----------------------------------------------*/
    ?>
</head>
<body>
<div id="loadingDiv">

</div>
<input type="hidden" name="idUser" value="<?php echo $session->getUserId(); ?>" id="idUser"/>
<?php
if ($session->getGrad() != 0) {
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
    <div id="macuri">

    </div>
   
    <div class="row">

    </div>
    <div class="spacer"></div>
    <?php
    if ($session->getGrad() != 0 OR $session->getGrad() != 3) {
        ?>
        <div id="iframeMetrologii">
            <button class="btn btn-sm, btn-primary inchidePanel">Inchide Frame</button>

            <iframe style="width:100%;" name="iframeMetrologii"></iframe>
        </div>
        <div style="height: 300px; display: none">
            <div id="tabeleResponsabili">

            </div>
        </div>

        <?php
    }
    ?>
    <?php if ($session->getGrad() == 0) { ?>
        <div class="row">
            <div class="col-md-12">
                <div id="iframeMetrologii">
                    <button class="btn btn-sm, btn-primary inchidePanel">Inchide Frame</button>

                   <iframe style="width:100%;" name="iframeMetrologii"></iframe>
                </div>
                <div id="tabeleResponsabili">
                    <?php
                        $rows_resps = $databFull->getResponsabiliLocatiiAparate($session->getInceputulLunii(), $session->getSfarsitulLunii());
                        $totalAmpera_locatii = $totalRed_locatii = $total_locatii = 0;
                        $totalAmpera_aparate = $totalRed_aparate = $total_aparate = 0;
                        $totalAmpera_depozit = $totalRed_depozit = $total_depozit = 0;
                        foreach ($rows_resps as $key => $row) {
                            $totalAmpera_locatii += $row->locatiiAmpera;
                            $totalAmpera_aparate += $row->aparateAmpera;
                            $totalAmpera_depozit += $row->depozitAmpera;

                            $totalRed_locatii += $row->locatiiRedlong;
                            $totalRed_aparate += $row->aparateRedlong;
                            $totalRed_depozit += $row->depozitRedlong;


                    ?>
                            <div class="col-md-3 height130 table-small">
                                <table class="table no-padding">
                                    <tr class="ampera-tr2" data-op="1" data-pers="<?php echo $row->idpers; ?>" data-locatii_tip="A">
                                        <td><span class="glyphicon  glyphicon glyphicon-user"></span><?php echo $row->nick; ?></td>
                                        <td>A(</td>
                                        <td><?php echo $row->locatiiAmpera; ?> L</td>
                                        <td> /</td>
                                        <td><?php echo $row->aparateAmpera; ?> A</td>
                                        <td>/</td>
                                        <td><?php echo $row->depozitAmpera; ?> AD</td>
                                        <td>/</td>
                                        <td>P(0</td>
                                        <td>/</td>
                                        <td>0</td>
                                        <td>))</td>
                                    </tr>
                                    <tr class="redlong-tr2" data-op="2" data-pers="<?php echo $row->idpers; ?>" data-locatii_tip="R">
                                        <td></td>
                                        <td>R(</td>
                                        <td><?php echo $row->locatiiRedlong; ?> L</td>
                                        <td> /</td>
                                        <td><?php echo $row->aparateRedlong; ?> A</td>
                                        <td>/</td>
                                        <td><?php echo $row->depozitRedlong; ?> AD</td>
                                        <td>/</td>
                                        <td>P(0</td>
                                        <td>/</td>
                                        <td>0</td>
                                        <td>))</td>
                                    </tr>
                                    <tr class="total-tr2" data-op="0" data-pers="<?php echo $row->idpers; ?>" data-locatii_tip="T">
                                        <td></td>
                                        <td>T(</td>
                                        <td><?php echo ($row->locatiiAmpera+$row->locatiiRedlong); ?> L</td>
                                        <td> /</td>
                                        <td><?php echo ($row->aparateAmpera+$row->aparateRedlong); ?> A</td>
                                        <td>/</td>
                                        <td><?php echo ($row->depozitAmpera+$row->depozitRedlong); ?> AD</td>
                                        <td>/</td>
                                        <td>P(0</td>
                                        <td>/</td>
                                        <td>0</td>
                                        <td>))</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <a href="castiguri.php?id=<?php echo $row->idpers ?>" class="btn btn-primary">Castiguri</a>
                                        </td>
                                        <td colspan="9">

                                        </td>
                                    </tr>
                                </table>
                            </div>
                    <?php
                        }
                        $total_locatii = $totalAmpera_locatii + $totalRed_locatii;
                        $total_aparate = $totalAmpera_aparate + $totalRed_aparate;
                        $total_depozit = $totalAmpera_depozit + $totalRed_depozit;
                    ?>
                    <div class="col-md-3 height130 table-small">
                        <table class="table no-padding">
                            <tr>
                                <td><span class="glyphicon  glyphicon glyphicon-user"></span> Total</td>
                                <td>A(</td>
                                <td><?php echo $totalAmpera_locatii; ?> L</td>
                                <td> /</td>
                                <td><?php echo $totalAmpera_aparate; ?> A</td>
                                <td>/</td>
                                <td><?php echo $totalAmpera_depozit; ?> AD</td>
                                <td>/</td>
                                <td>P(0</td>
                                <td>/</td>
                                <td>0</td>
                                <td>))</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>R(</td>
                                <td><?php echo $totalRed_locatii; ?> L</td>
                                <td> /</td>
                                <td><?php echo $totalRed_aparate; ?> A</td>
                                <td>/</td>
                                <td><?php echo $totalRed_depozit;?> AD</td>
                                <td>/</td>
                                <td>P(0</td>
                                <td>/</td>
                                <td>0</td>
                                <td>))</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>T(</td>
                                <td><?php echo $total_locatii; ?> L</td>
                                <td> /</td>
                                <td><?php echo $total_aparate; ?> A</td>
                                <td>/</td>
                                <td><?php echo $total_depozit; ?> AD</td>
                                <td>/</td>
                                <td>P(0</td>
                                <td>/</td>
                                <td>0</td>
                                <td>))</td>
                            </tr>
                            <tr>
                                <td colspan="3">

                                </td>
                                <td colspan="9">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-3 height130 table-small">
                        <table class="table no-padding">
                            <tr>
                                <td><span class="glyphicon  glyphicon glyphicon-user"></span>Links</td>
                                <td colspan="5">
                                    <form target="_blank" action="http://86.122.183.194/minister/main.php" method="post">
                                        <input type="submit" name="minister" value="Minister Ampera"
                                               class="btn btn-sm btn-primary"/>
                                    </form>
                                </td>
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
                                <td colspan="5"><a href="http://redlong.ro/dev/main.php" class="btn btn-sm btn-primary"
                                                   target="_blank">Contori R</a><a href="http://86.122.183.194/dev/main.php"
                                                                                   class="btn btn-sm btn-primary"
                                                                                   target="_blank">Contori A</a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php
            if ($session->getGrad() == 0 OR $session->getGrad() == 3) {
                $errori = $db->getErrorsByPers($id_persoana, $session->getOperator());
                $locatiiCuErori = 0;
                foreach ($errori as $key => $value) {
                    foreach ($value as $cheie => $valoare) {
                        if ($valoare != 0) {
                            $locatiiCuErori += 1;
                        }
                    }
                }
                $row_resp = $databFull->getResponsabiliLocatiiAparate($session->getInceputulLunii(), $session->getSfarsitulLunii(), $session->getIdresp());
                $responsabil = $row_resp[0];
        ?>
        <?php
            /********************************************************************************************************************
            |                                                                                                                   |
            |    RESPONSABIL                                                                                                    |
            |                                                                                                                   |
            ********************************************************************************************************************/
        ?>
            <div class="col-md-3 col-sm-3" style="width: 20%">
                <div class="panel panel-primary" id="locatiiPanel">
                    
                    <div class="panel-heading" id="locatiiPanel_header">
                        <strong><span class="glyphicon  glyphicon glyphicon-user"></span><?php echo $responsabil->nick; ?> </strong>
                        <italic style="display:block;"><?php
                            if ($session->getOperator() == 1) {
                                echo 'A' . '(' . $responsabil->locatiiAmpera. 'L / ' . $responsabil->aparateAmpera . 'A / ' . $responsabil->depozitAmpera . 'AD ) P(0/0)';
                            } elseif ($session->getOperator() == 2) {
                                echo 'R' . '(' . $responsabil->locatiiRedlong. 'L / ' . $responsabil->aparateRedlong . 'A / ' . $responsabil->depozitRedlong . 'AD ) P(0/0)';
                            } else {
                                echo 'T' . '(' . $responsabil->totalLocatii. 'L / ' . $responsabil->totalAparate . 'A / ' . $responsabil->totalDepozitAparate . 'AD ) P(0/0)';
                            }
                            ?>
                        </italic>
                        <span style="display: inline-block;width: 68%;">
                            <input type="text" name="locatie" class="form-control" placeholder="Numele Locatiei"
                                   id="locatieAuto"/>
                        </span>
                        <span style='display: inline-block;width :32%; float:right; '>
                            <a href="?id_resp=<?php echo $session->getIdresp() ?>&operator=<?php echo $session->getOperator() ?>&type=culoareAparat&sort=DESC"><img
                                    src='css/images/green_light.png' style='width:20px; height: 20px;'/></a>
                            <a href="?id_resp=<?php echo $session->getIdresp() ?>&operator=<?php echo $session->getOperator() ?>&type=culoareAparat&sort=ASC"><img
                                    src='css/images/red_light.png' style='width:20px; height: 20px;'/></a>
                            <?php if ($locatiiCuErori != 0) { ?><a
                                href="?id_resp=<?php echo $session->getIdresp() ?>&operator=<?php echo $session->getOperator() ?>&type=error&sort=DESC" >
                                    <img src='css/images/triangle_red.png' style='width:20px; height: 20px;'/>
                                </a>
                                <?php
                                echo $locatiiCuErori;
                            }
                            ?>
                        </span>
                        <input type="hidden" id="id_pers" value="<?php echo $session->getIdresp();?>"/>
                    </div>
                    <?php
                        /********************************************************************************************************************
                        |    LOCATII RESPONSABIL                                                                                            |
                        ********************************************************************************************************************/
                    ?>
                    <div class="panel-body" style="padding:0px;" id="locatiiPanel_locatii">
                        <script>
                            $(document).ready(function () {
                                $('#locatieAuto').instaFilta();
                            });
                            $(function () {
                                $("#tabs").tabs();
                            });
                        </script>

                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1">Aparate</a></li>
                                <li><a href="#tabs-2">Locatii</a></li>
                                <li><a href="#tabs-3">Error</a></li>
                            </ul>
                            <?php
                                /*------------------------------------------------------------------------------------------------------------------
                                |    ACTIVITARE APARATE                                                                                                 |
                                ------------------------------------------------------------------------------------------------------------------*/
                            ?>
                            <div id="tabs-1" style="padding:0px">
                                <ul class="list-group">
                                <?php
                                    // $locatii = $db->getLocatii($session->getOperator(), $session->getIdresp(), $an, $luna, $sort);
                                    $totalAparateResponsabil = 0;
                                    $i = 1;
                                    $type = (isset($_GET['type'])) ? $_GET['type'] : 'culoareAparat';
                                    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'DESC';
                                    $tip_sortare = 'ord';
                                    $IdResp = (isset($_GET[id_resp])) ? intval($_GET[id_resp]) : $session->getIdresp();
                                    $IdOperator = (isset($_GET[operator])) ? intval($_GET[operator]) : $session->getOperator();
                                    $rows_locatii = $databFull->getLocatiiResponsabil($IdOperator, $IdResp, $an, $luna, $type, $sort, $tip_sortare);
                                    foreach ($rows_locatii as $key => $value) {
                                        $id_locatie = $value['idlocatie'];
                                        $denumireLocatie = $value['denumire'];
                                        $nrAparate = $value['nrAparate'];
                                        $culoareAparat = $value['culoareAparat'];
                                        $idOperator = $value['idOperator'];
                                        $aparateLocatie = $value['aparate'];
                                        $stare_aparate = $value['stare_aparate'];
                                        $nrAparateLocatie = count($aparateLocatie);
                                        $operator = $databFull->getNumeOperatorLocatie($value['idlocatie']);
                                        if ($i == 1) {
                                            $operator_init = $operator;
                                            echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                        } else {
                                            if ($operator_init != $operator) {
                                                $operator_init = $operator;
                                                echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                            }
                                        }
                                ?>

                                        <li class="list-group-item instafilta-target" style="display :inline-block; width: 100%; max-height: 300px; padding: 0px 0px;">
                                            <span class="pull-left">
                                                <a class="getLocatie2" data-luna="<?php echo $luna ?>"
                                                   data-an="<?php echo $an ?>"
                                                   id="<?php echo $session->getIdLocatie() == $id_locatie ? 'selected' : ''; ?>"
                                                   href="#" data-pers="<?php echo $session->getIdresp() ?>"
                                                   data-locatie="<?php echo $id_locatie; ?>">
                                <?php
                                                    echo $i . '. ' . $page->maxText($denumireLocatie, 15);
                                                    $i++;
                                ?>
                                                </a>
                                            </span>
                                            <span class="pull-right">
                                                <span>
                                <?php
                                                    foreach ($stare_aparate as $cheie => $val) {
                                                        echo $val.' ';
                                                    }
                                ?>
                                                </span>
                                <?php 
                                                echo $nrAparateLocatie; 
                                ?>
                                                AP
                                            </span>
                                        </li>
                                <?php
                                        $totalAparateResponsabil += $nrAparateLocatie;
                                    }
                                ?>
                                    <li>
                                        <br />
                                        <span class="pull-left">Total locatii respnsabil: <?php echo ($i-1); ?></span><br />
                                        <span class="pull-left">Total aparate respnsabil: <?php echo $totalAparateResponsabil; ?></span><br />
                                    </li>
                                </ul>
                            </div>
                            <?php
                                /*------------------------------------------------------------------------------------------------------------------
                                |    NUMAR APARATE                                                                                                 |
                                ------------------------------------------------------------------------------------------------------------------*/
                            ?>
                            <div id="tabs-2" style="padding: 0px;">
                                <ul class="list-group">
                                    <?php
                                        $i = 1;
                                        foreach ($rows_locatii as $key => $value) {
                                            $id_locatie = $value['idlocatie'];
                                            $denumireLocatie = $value['denumire'];
                                            $nrAparate = $value['nrAparate'];
                                            $culoareAparat = $value['culoareAparat'];
                                            $idOperator = $value['idOperator'];
                                            $aparateLocatie = $value['aparate'];
                                            $stare_aparate = $value['stare_aparate'];
                                            $nrAparateLocatie = count($aparateLocatie);
                                            $operator = $databFull->getNumeOperatorLocatie($value['idlocatie']);
                                            if ($i == 1) {
                                                $operator_init = $operator;
                                                echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                                $class_first = 'first activated';
                                            } else {
                                                if ($operator_init != $operator) {
                                                    $operator_init = $operator;
                                                    echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                                    $class_first = '';
                                                }
                                            }
                                    ?>
                                            <li class="list-group-item instafilta-target" style="max-height: 300px; min-height: 40px;">
                                                <span class="pull-left">
                                                    <a class="getLocatie2" data-luna="<?php echo $luna ?>"
                                                       data-an="<?php echo $an ?>"
                                                       id="<?php echo $session->getIdLocatie() == $id_locatie ? 'selected' : ''; ?>"
                                                       href="#" data-pers="<?php echo $session->getIdresp() ?>"
                                                       data-locatie="<?php echo $id_locatie; ?>">
                                    <?php
                                                        echo $i . '. ' . $page->maxText($denumireLocatie, 15);
                                                        $i++;
                                    ?>
                                                    </a>
                                                </span>
                                                <span class="pull-right"> <?php echo $nrAparateLocatie; ?> AP</span>
                                            </li>
                                    <?php
                                        }
                                    ?>
                                </ul>

                            </div>
                            <?php
                                /*------------------------------------------------------------------------------------------------------------------
                                |    ERORI APARATE                                                                                                 |
                                ------------------------------------------------------------------------------------------------------------------*/
                            ?>
                            <div id="tabs-3" style="padding:0px; display : block;">
                                <ul class="list-group">
                                    <?php
                                        $i = 1;
                                        foreach ($rows_locatii as $key => $value) {
                                            $id_locatie = $value['idlocatie'];
                                            $denumireLocatie = $value['denumire'];
                                            $nrAparate = $value['nrAparate'];
                                            $culoareAparat = $value['culoareAparat'];
                                            $idOperator = $value['idOperator'];
                                            $aparateLocatie = $value['aparate'];
                                            $aparate_err = $value['aparate_err'];
                                            $aparate_all = $value['aparate_all'];
                                            $nrAparateLocatie = count($aparateLocatie);
                                            $operator = $databFull->getNumeOperatorLocatie($value['idlocatie']);
                                            if ($i == 1) {
                                                $operator_init = $operator;
                                                echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                            } else {
                                                if ($operator_init != $operator) {
                                                    $operator_init = $operator;
                                                    echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                                }
                                            }
                                    ?>

                                            <li class="list-group-item instafilta-target" style="display :inline-block; width: 100%; max-height: 300px;">
                                                <span class="pull-left">
                                                    <a class="getLocatie2" data-luna="<?php echo $luna ?>"
                                                       data-an="<?php echo $an ?>"
                                                       id="<?php echo $session->getIdLocatie() == $id_locatie ? 'selected' : ''; ?>"
                                                       href="#" data-pers="<?php echo $session->getIdresp() ?>"
                                                       data-locatie="<?php echo $id_locatie; ?>">
                                    <?php
                                                        echo $i . '. ' . $denumireLocatie;
                                                        $i++;
                                    ?>
                                                    </a>
                                                </span>
                                                <span class="pull-right">
                                                    <span>
                                    <?php
                                                        foreach ($aparate_err as $cheie => $val) {
                                    ?>
                                                            <img width="10px" height="10px"
                                                                serie-Aparat="<?php echo $aparate_all[$cheie]['seria'] ?>"
                                                                data-idAparat="<?php echo $cheie; ?>"
                                                                class="eroriPic"
                                                                src="css/images/<?php echo $val == 0 ? 'triangle_blue.png' : 'triangle_red.png' ?>"/>
                                    <?php
                                                        }
                                    ?>
                                                    </span>
                                                    <?php //echo $nrAparateLocatie.' AP'; ?> 
                                                </span>
                                            </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            } 

            /********************************************************************************************************************
            |                                                                                                                   |
            |    APARATE LOCATIE SELECTATA                                                                                      |
            |                                                                                                                   |
            ********************************************************************************************************************/
        ?>
        <div id='locatieTarget' style="padding-left: 0px; width: 80%" 
            class="col-md-<?php echo ($session->getGrad() == 0 OR $session->getGrad() == 3) ? 9 : 12 ?>  col-sm-<?php echo ($session->getGrad() == 0 OR $session->getGrad() == 3) ? 9 : 12; ?>">
            <?php
            if (isset($_SESSION['idLocatie'])) {
                $id_locatie = $session->getIdLocatie();
            }
            echo $page->createInput('hidden', 'idLocatie', $id_locatie, 'id="idLocProdInv"');
            ?>
            <div class="panel panel-primary" id="mainPanel">
                <?php 
                    $objInfoLocatie = $databFull->getLocatieInfo($id_locatie); 
                    /*------------------------------------------------------------------------------------------------------------------
                    |    date locatie                                                                                                  |
                    ------------------------------------------------------------------------------------------------------------------*/
                ?>
                <div  class="panel-heading">
                    <span class="width80">
                        <?php echo '<strong>' . $objInfoLocatie->nickLocatie . '</strong> / ' . $objInfoLocatie->denumire . ' / ' . $objInfoLocatie->adresa ?>
                    </span>
                    <span class="text-right width20">
                        <strong> IdOp :</strong><?php echo $objInfoLocatie->idOperator; ?>
                        <strong> IdLoc :</strong><?php echo $objInfoLocatie->idlocatie; ?>
                     </span>
                    <input type='hidden' id="aparate_idOp" value="<?php echo $objInfoLocatie->idOperator; ?>">
                    <input type='hidden' id="aparate_idlocatie" value="<?php echo $objInfoLocatie->idlocatie; ?>">
                    <input type='hidden' id="aparate_idpers" value="<?php echo $responsabil->idpers; ?>">
                    <a id="tabel-sas" 
                        class="btn btn-warning btn-md" data-idloc="<?php echo $id_locatie;?>" data-op="<?php echo $objInfoLocatie->idOperator;?>" data-resp="<?php echo $session->getIdresp();?>">
                        SAS
                    </a>
                    <?php
                        /*------------------------------------------------
                         *  BUTOANE RAPOARTE - SILVIU - 18.10.2016
                         *----------------------------------------------*/
                    ?>
                            <button type="button" class="btn btn-default btn-sm btn-rapoarte btn-siz" data-locatie="<?php echo $objInfoLocatie->idlocatie; ?>"  data-firma="<?php echo $objInfoLocatie->idfirma; ?>">Raport SIZ</button>
                            <button type="button" class="btn btn-default btn-sm btn-rapoarte btn-sil" data-locatie="<?php echo $objInfoLocatie->idlocatie; ?>"  data-firma="<?php echo $objInfoLocatie->idfirma; ?>">Raport SIL</button>
                    <?php
                        /*------------------------------------------------
                         *  END BUTOANE RAPOARTE
                         *----------------------------------------------*/
                    ?>
                </div>
                <div class="body">
                        <?php
                        if (isset($locatii)) {
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
                        }
                        if ($session->getGrad() == 0 OR $session->getGrad() == 3) {
                            ?>

                        <?php } ?>
                        <form method="POST" id="containerDepozit">
                            <?php ?>
                            <table class="table-bordered table-striped table-condensed cf col-md-12 tabel-raport zoomable">
                                <thead>
                                <tr>
                                    <th>Nr.</th>
                                    <th class="centered">Poz</th>
                                    <th class="centered">ID AP</th>
                                    <th class="centered">Seria</th>
                                    <th class="centered">Tip</th>
                                    <th class="centered">V</th>
                                    <th class='centered'>Err</th>
                                    <th class="centered">Total In</th>
                                    <th class="centered">Total Out</th>
                                    <th></th>
                                    <th class="centered">Data</th>
                                    <th class="centered">Wan/3G</th>
                                    <th class="centered">Inactivitate</th>
                                </tr>
                                </thead>
                                <tbody id="aparateTarget">
                                <?php
                                $eroriAparate = $db->verificaErroriIndex($id_locatie);
                                $aparateInfo = $db->getInfoAparate($id_locatie, $id_persoana, $luna, $an);
                                $j = 1;
                                /*------------------------------------------------------------------------------------------------------------------
                                |    LISTARE APARATE                                                                                               |
                                ------------------------------------------------------------------------------------------------------------------*/
                                if (count($aparateInfo) > 0) {
                                    foreach ($aparateInfo as $objInfoAparate) {
                                        $pachete = $db->getPacheteAparat($objInfoAparate->idAparat, $an, $luna, date('d'));
                                        $style_noip = (!$objInfoAparate->ipPic) ? 'color: red' : '';
                            ?>
                                        <tr class="rowAparat <?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
                                            <td rowspan="2"><?php
                                                echo $j;
                                                $j++;
                                                ?></td>
                                            <td rowspan="2"><input class="noClick" type="text" style="width:20px;"
                                                                   size="2"
                                                                   name="aparat-<?php echo $objInfoAparate->idAparat; ?>"
                                                                   value="<?php echo $objInfoAparate->pozitieLocatie; ?>"/>
                                            </td>
                                            <td rowspan="2" style="width: 90px; font-size: 16px;">
                                                <?php
                                                $url = "http://$objInfoAparate->ipPic:" . ($objInfoAparate->pozitieLocatie + 60);
                                                ?>
                                                <img
                                                    src="css/images/<?php echo ($page->checkPic($url) == TRUE) ? 'green_light.png' : 'red_light.png'; ?>"
                                                    style="width:20px; height:20px;"/>
                                                <a class="ipPic"
                                                   href="http://<?php echo $objInfoAparate->ipPic; ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>" style="<?php echo $style_noip;?>"><?php echo $objInfoAparate->idAparat; ?></a>
                                                || <a class="techPic"
                                                      href="http://admin:ampera@<?php echo $objInfoAparate->ipPic ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>/tech/">T</a></a>
                                                <?php
                                                $a->format('Y-m-d H:i:s');
                                                $datetime2 = date_create($objInfoAparate->ultimaConectare, $b);
                                                $interval = date_diff($datetime2, $a);
                                                $diferentaOre = $interval->format('%h');
                                                ?></td>
                                            <td rowspan="2" style="font-size: 16px;"><?php echo $objInfoAparate->seria; ?></td>
                                            <td rowspan="2" style="font-size: 16px;"><?php echo $page->maxText($objInfoAparate->tip, 2); ?></td>
                                            <td rowspan="2" style="font-size: 16px;"><?php echo $objInfoAparate->verSoft ?></td>
                                            <td rowspan="2" style="width: 24px; text-align:center; vertical-align:middle;">
                                                <img
                                                    src='css/images/triangle_<?php echo ($db->getNrEroriAparat($objInfoAparate->idAparat) == 0) ? 'blue' : 'red'; ?>.png'
                                                    class='eroriPic' serie-Aparat='<?php echo $objInfoAparate->seria ?>' mac-aparat='<?php echo $objInfoAparate->macPic; ?>' data-idAparat='<?php echo $objInfoAparate->idAparat ?>'/>
                                                <?php
                                                if ($db->getNrEroriAparat($objInfoAparate->idAparat) > 0) {
                                                    echo $db->tipEroare($objInfoAparate->idAparat);
                                                }
                                                //echo $objInfoAparate->bitiStare;
                                                //echo decbin( $objInfoAparate->bitiStare );
                                                // if ($objInfoAparate->bitiStare != 0){
                                                    $bin = decbin($objInfoAparate->bitiStare);
                                                    // $bin = substr("0000",0,16 - strlen($bin)) . $bin;
                                                    $extra_bin = 16 - strlen($bin); 
                                                    $extra_added = '';
                                                    for ($i=0; $i < $extra_bin; $i++) { 
                                                        $extra_added .= '0';                                
                                                    } 
                                                    $bin = $extra_added.$bin;                              
                                                    $rest = substr($bin, -13, 1);
                                                    if ($rest == 0) {
                                                        echo "<img src='css/images/add.png' height='16' width='16'>";
                                                    }
                                                // }
                                                
                                                ?>
                                                test
                                            </td>
                                            <?php $valori = $db->getCashInCashOut($objInfoAparate->idAparat, $an, $luna, date('d')); ?>
                                            <td style="border-left-width: 0px;border-right-width: 1px;padding-right: 0px;padding-left: 2px;font-size: 16px;height: 31px; width: 150px;"><?php echo $page->getIndexReset($objInfoAparate->lastidxInM); ?> <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastidxInM)); ?>  
                                            </td>
                                            <td style="border-left-width: 1px;border-right-width: 0px;padding-right: 0px;padding-left: 2px;font-size: 16px;height: 31px; width: 150px;" ><?php echo $page->getIndexReset($objInfoAparate->lastidxOutM); ?> <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastidxOutM)); ?>  
                                            </td>
                                            <td class="grey" style="width: 6%;"><?php echo $valori['cashIn']; ?> / <?php echo $valori['cashOut']; ?></td>
                                            <td><?php
                                                echo $page->afiseazaData($objInfoAparate->ultimaConectare);
                                                $diferentaZile = $interval->format('%a');
                                                ?></td>
                                            <td>
                                                <?php echo isset($pachete->nrPacWan) ? $pachete->nrPacWan : 0; ?> / <?php echo isset($pachete->nrPac3g) ? $pachete->nrPac3g : 0; ?>
                                            </td>
                                            <td><?php echo $db->getDataPornire($objInfoAparate->idAparat, $an, $luna, date('d')); ?>
                                                    
                                                 <?php echo $objInfoAparate->dtLastM;?>
                                            </td>
                                        </tr>
                                        <?php $pachetePrecedente = $db->getPacheteAparat($objInfoAparate->idAparat, $an, $luna, date('d') - 1) ?>
                                        <tr class="<?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
                                            
                                            <td style="border-left-width: 0px;border-right-width: 1px;padding-right: 0px;padding-left: 2px;font-size: 16px;height: 31px; width: 150px;"><?php echo $page->getIndexReset($objInfoAparate->lastIdxInE); ?> <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastIdxInE)); ?>
                                            </td>
                                            <td style="border-left-width: 1px;border-right-width: 0px;padding-right: 0px;padding-left: 2px;font-size: 16px;height: 31px; width: 150px;">
                                            <?php echo $page->getIndexReset($objInfoAparate->lastIdxOutE); ?> <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastIdxOutE)); ?>
                                            </td>
                                            <?php $valori = $db->getCashInCashOutElectronic($objInfoAparate->idAparat, $an, $luna, date('d')); ?>
                                            <td class="grey"><?php echo ($valori['cashIn'])/100; ?> / <?php echo ($valori['cashOut'])/100; ?></td>
                                            <td style="width: 15.5%;font-size: 16px;">MAC: <?php echo $objInfoAparate->macPic; ?></td>
                                            <td></td>
                                            <td><img
                                                    src="css/images/<?php echo ($diferentaOre >= 1 or $diferentaZile >= 1) ? 'red_light.png' : 'green_light.png' ?>"
                                                    style="width: 20px; height : 20px; float:right"/><?php
                                                if ($diferentaZile == 0) {
                                                    echo $interval->format('%hh%im%ss');
                                                } else {
                                                    echo $interval->format('%R%a zile');
                                                }
                                                ?></td>
                                        </tr>
                                        <tr class="aparateActions">
                                            <td colspan="13" style="position: relative">
                                                <a href="#" class="btn btn-sm btn-primary">Poze Teren</a>
                                                <a href="#" class="btn btn-sm btn-primary extra">PVA</a>
                                                <a href="#" class="btn btn-sm btn-primary"><span
                                                        class="glyphicon glyphicon-random"></span></a>
                                                <a href="#" class="btn btn-sm btn-primary">D</a>
                                                <a href="#" class="btn btn-sm btn-primary extra">VT</a>
                                                <a href="ftp://acte:acte77@rodiz.ro/metrologii/curente/<?php echo $objInfoAparate->seria ?>.pdf"
                                                   target="_blank"
                                                   class="btn btn-sm btn-primary metrologii">Metrologie</a>
                                                <a href='ftp://acte:acte77@rodiz.ro/autorizatii/curente/<?php echo $objInfoAparate->seria; ?>.pdf'
                                                   class='btn btn-sm btn-primary autorizatii'>Autorizatii</a>
                                                <a href="idxZile.php?idAparat=<?php echo $objInfoAparate->idAparat; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>"
                                                   class="btn btn-primary">Idx Zile</a>
                                                <a href="interfataPic/game.php?seria=<?php echo $objInfoAparate->seria; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>"
                                                   class="btn btn-success configurare" target="_blank">Configurare</a>
                                                <a class='btn btn-sm btn-primary istoricAparate'
                                                    data-id="<?php echo $objInfoAparate->idAparat; ?>"
                                                    data-seria="<?php echo $objInfoAparate->seria; ?>">
                                                    H
                                                </a>
                                               <div class="loading"><img src="css/AjaxLoader.gif" /></div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                /*--- end date locatie --------------------------------------------------------------------------------------*/
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
                    <?php
                        /*------------------------------------------------------------------------------------------------------------------
                        |    BUTOANE GENERALE LOCATIE                                                                                      |
                        ------------------------------------------------------------------------------------------------------------------*/
                    ?>
                    <div class="butoane">
                        <a href="<?php echo DOMAIN ?>/rapoarte/raportzilnic.php?id=<?php echo $objInfoLocatie->idlocatie; ?>"
                           class="btn btn-primary btn-md">Raport Zilnic</a>
                        <a href="rapoarte/raportlunar.php" class="btn btn-primary btn-md">Raport Lunar</a>
                        <a href="#" class="btn btn-primary" id="save-form-submit">Salveaza Pozitie Locatie</a>
                        <a href="#" class="btn btn-primary" id="inventar">Adauga obiecte in inventar</a>
                        <a href="#" class="btn btn-primary" id="changeZoom">Activeaza Zoom</a>
                        <?php
                        $footer = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                         <button type=\"button\" class=\"btn btn-primary\" id='salveazaInventar'>Introdu Element</button>";
                        $formular = $page->createFieldset('text', 'denumire', 'Denumire Produs', "id='denProdInv' class='form-control'");
                        $formular .= $page->createFieldset('text', 'cantitate', 'Cantitate in bucati', "id='canProdInv' class='form-control'");
                        $formular .= $page->createFieldset('text', 'stare', 'Stare Produse', "id='stareProdInv' class='form-control'");
                        $formular .= $page->createFieldset('text', 'observatii', 'Observatii', "id='observatiiProdInv' class='form-control'");
                        echo $page->createModal('inventar-modal', 'Adauga Elemente In Inventar', $formular, $footer);
                        ?>
                        <a href="#" class="btn btn-success" id="refreshLocatie">Refresh Locatie</a>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#refreshLocatie').click(function (event) {
                                    event.preventDefault();
                                    var idLocatie = $('#idLocProdInv').attr('placeholder');
                                    var idPers = <?php echo $id_persoana ?>;
                                    var an = <?php echo $an; ?>;
                                    var luna = <?php echo $luna; ?>;
                                    $.ajax({
                                        type: "POST",
                                        url: 'ajax/aparateAjax.php',
                                        data: {
                                            'idLocatie': idLocatie,
                                            'idPersoana': idPers,
                                            'an': an,
                                            'luna': luna
                                        },
                                        success: function (result) {
                                            var loader = $('#loader').html();
                                            $('#aparateTarget').html(loader);
                                            setTimeout(function () {
                                                $('#aparateTarget').html(result);
                                            }, 1000);

                                        }
                                    });
                                });
                            });
                        </script>
                        <div class="panel panel-info">
                            <div class="panel-heading" id="infoFirma">Click pentru informatii locatie
                                ||
                                <?php
                                $net = $db->getNetByLocation($id_locatie);
                                foreach ($net as $netElement) {
                                    switch ($netElement->tip) {
                                        case 1 :
                                            echo 'Router1 - ';
                                            break;
                                        case 2 :
                                            echo 'Router2 - ';
                                            break;
                                        case 3 :
                                            echo 'Camere';
                                            break;
                                    }
                                    ?>
                                    <a href="http://<?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic : ''; ?>:<?php echo $netElement->port ?>"
                                       target="_blank">
                                        <?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic : ''; ?>
                                    </a> ||
                                    <?php
                                }
                                ?>|| 3G IP - <a
                                    href="http://<?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic3g : ''; ?>"><?php echo (key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic3g : ''; ?></a>
                            </div>
                            <div class="panel-body" style="display : none;">
                                <?php ?>
                                <table class="table table-responsive">
                                    <tr>
                                        <td><strong>Fond:</strong> <?php echo $objInfoLocatie->fond; ?></td>
                                        <td><strong>Incasari noi:</strong> 0</td>
                                        <td><strong>Incasari restante:</strong> 0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <strong>Detinator:</strong> <?php echo $objInfoLocatie->denumire; ?>
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
                                <form method="POST">
                                    <table class="table table-responsive">
                                        <input type="hidden" name="loc" value="<?php echo $id_locatie; ?>"/>
                                        <input type="hidden" value="<?php echo(isset($net[1]) ? 'update' : 'insert') ?>"
                                               name="actiune"/>
                                        <?php for ($i = 1; $i < 4; $i++) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    switch ($i) {
                                                        case 1 :
                                                            echo 'Rooter 1';
                                                            break;
                                                        case 2 :
                                                            echo 'Rooter 2';
                                                            break;
                                                        case 3 :
                                                            echo 'Rooter 3';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <fieldset style="display:inline-block">
                                                        Port :
                                                        <input type="text"
                                                               name="port_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                               size="2" class="noClick"
                                                               value="<?php echo(isset($net[$i]) ? $net[$i]->port : '80'); ?>"/>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset style="display:inline-block">
                                                        User:
                                                        <input type="text"
                                                               name="user_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                               class="noClick"
                                                               value="<?php echo(isset($net[$i]) ? $net[$i]->valUser : 'user'); ?>"/>
                                                    </fieldset>
                                                </td>
                                                <td>
                                                    <fieldset style="display:inline-block">
                                                        Parola
                                                        <input type="text"
                                                               name="parola_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                               class="noClick"
                                                               value="<?php echo isset($net[$i]) ? $net[$i]->valPass : 'parola'; ?>"/>
                                                    </fieldset>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="4">
                                                <fieldset style="display:inline-block">
                                                    <input type="hidden" name="idLocatie"
                                                           value="<?php echo $id_locatie; ?>"/>
                                                    <button type="submit" name="form-rooter-locatie"
                                                            value="form-rooter-locatie" class="btn btn-primary">Salveza
                                                    </button>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div id="loader">
                            <img src="css/AjaxLoader.gif"/>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading" id="panel-angajati">Click aici pentru a vizualiza angajatii. </div>
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
                                    <?php
                                    $angajati = $db->getAngajati($id_locatie);
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
                                        <?php
                                        $i++;
                                    }

                                    $footerPersonal = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                         <button type=\"button\" class=\"btn btn-primary\" id='salveazaPersonal'>Introdu Angajat</button>";
                                    $formularPersonal = $page->createFieldset('text', 'nume', 'Numele de familie', "id='numePersonal' class='form-control'");
                                    $formularPersonal .= $page->createFieldset('text', 'prenume', 'Prenumele', "id='prenumePersonal' class='form-control'");
                                    $formularPersonal .= $page->createFieldset('text', 'nick', "Nick", "id='nickPersoana' class='form-control'", 1);
                                    $formularPersonal .= $page->createFieldset('text', 'telefon', 'Telefon', "id='telefonPersonal' class='form-control'", 1);
                                    $formularPersonal .= $page->createFieldset('email', 'émail', 'Email', "id='emailPersonal' class='form-control'");
                                    echo $page->createModal('addPersonal', 'Adauga Personal la locatie', $formularPersonal, $footerPersonal);
                                    ?>
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
                                    <?php
                                    $inventar = $db->getInventar($id_locatie);
                                    $k = 1;
                                    foreach ($inventar as $obiect) {
                                        ?>
                                        <tr>
                                            <td><?php
                                                echo $k;
                                                $k++;
                                                ?></td>
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
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                <?php
                    // if ((isset($_SESSION['locatii_tip'])) && ($_SESSION['locatii_tip'] == 'T')) {
                    if (isset($_SESSION['locatii_tip'])) {
                ?>
                        main.show_locatii_total_header('<?php echo $session->getIdresp();?>', '<?php echo $session->getOperator();?>','DESC',  'culoareAparat'); 
                        main.show_locatii_total('<?php echo $session->getIdresp();?>', '<?php echo $session->getOperator();?>', 'DESC', 'culoareAparat');  
                <?php
                    }
                ?>

                


            });
            var getUrlParameter = function getUrlParameter(sParam) {
                var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
                }
            };
            $(document).on('click', '.ipPic', function (event) {
                event.preventDefault();
                event.stopImmediatePropagation();
                var url = $(this).attr('href');
                var height = $('#tabeleResponsabili').parent().height();
                $('#iframeMetrologii iframe').attr('src', url);
                $('#iframeMetrologii iframe').css({'height': height});
                $('#tabeleResponsabili').hide();
                $('#iframeMetrologii').show();
            });
            $(document).on('click', '.noClick', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });
            $(document).on('click', '.techPic', function (event) {
                event.preventDefault();
                event.stopImmediatePropagation();
                var url = $(this).attr('href');
                var height = $('#tabeleResponsabili').parent().height();
                $('#iframeMetrologii iframe').attr('src', url);
                $('#iframeMetrologii iframe').css({'height': height});
                $('#tabeleResponsabili').hide();
                $('#iframeMetrologii').show();
            });

            $(document).on('click', '.eroriPic', function (event) {
                event.stopImmediatePropagation();
                var idAparat = $(this).attr('data-idAparat');
                var serieAparat = $(this).attr('serie-aparat');
                var url = 'http://86.122.183.194/pic/index.php?idAparat=' + idAparat +'&serieAparat=' + encodeURIComponent(serieAparat);
                var height = $('#tabeleResponsabili').parent().height();
                $('#iframeMetrologii iframe').attr('src', url);
                $('#iframeMetrologii iframe').css({'height': height});
                $('#tabeleResponsabili').hide();
                $('#iframeMetrologii').show();
            });
            $(document).on('click', '.getLocatie', function (event) {
                event.preventDefault();

                var idLoc = $(this).attr('data-locatie');
                $.ajax({
                    type: "POST",
                    url: DOMAIN + '/router.php',
                    data: {
                        'idLocatie': idLoc
                    },
                    success: function (result) {
                        location.reload();
                    }
                });
            });
            $(document).on("click", ".eroriTotale", function (event) {
                event.stopImmediatePropagation();
                var id = $(this).attr('data-id');
                location.href = "<?php echo DOMAIN ;?>/rapoarte/erori.php?id=" + id;
            })
            $(function () {
                window.setInterval("$('.blink').toggle();", 500);
            });
        </script>
    <?php
        include "includes/modals.php";
    ?>
    <?php
        /*------------------------------------------------
         *  MODALE RAPOARTE - SILVIU - 18.10.2016
         *----------------------------------------------*/
            include 'includes/rapoarte/modala-siz.php';
        /*------------------------------------------------
         *  END MODALE RAPOARTE
         *----------------------------------------------*/
    ?>
</body>
</html>