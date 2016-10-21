<?php
require_once "autoloader.php";
require_once('uploadFile.php');
require_once "includes/class.db.php";
require_once "includes/class.databFull.php";
$page = new PageClass();
$appSettings = $session = new SessionClass();
$session->exchangeArray($_SESSION);

$databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
$page->checkLogin($session->getUsername(), $session->getOperator());

$_get = $databFull->sanitize($_GET);
$_post = $databFull->sanitize($_POST);


$id_persoana = (isset($_GET['id_pers'])) ?  intval($_GET['id_pers']) :  $session->getIdresp();
$session->setOperator((isset($_GET['op'])) ?  intval($_GET['op']) :  1);
if ($session->getGrad() != 0) {
    $id_locatie = $session->getIdLocatie();
}
$luna = (isset($_GET['luna'])) ? intval($_get['luna']) : date('n');
$an = (isset($_GET['an'])) ? intval($_get['luna']) : date('Y');
$id_locatie = (isset($_GET['id_locatie'])) ? intval($_get['id_locatie']) : $session->getIdLocatie();
$id_locatie = (isset($_GET['sort'])) ? $_get['sort'] : 'DESC';

if (isset($_POST['form-rooter-locatie']) AND $_POST['form-rooter-locatie'] == "form-rooter-locatie") {
    $post = $databFull->sanitize($_POST);
    if ($post['actiune'] == 'insert') {
        for ($i = 1; $i < 4; $i++) {
            if ($databFull->insertIntoNet($post['loc'], $post['port_' . $i], $i, $post['user_' . $i], $post['parola_' . $i])) {
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
            if ($databFull->updateIntoNet($key, $value['port'], $value['user'], $value['pass'])) {
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
                            $user_info = $databFull->verifyUser($_SESSION['username'], NULL);
                            if (isset($_POST['form-name']) AND $_POST['form-name'] == 'pass') {

                                $verificare = $page->checkPasswords($user_info->pass, $_POST['oldPass'], $_POST['parolaNoua1'], $_POST['parolaNoua2']);
                                if (!is_bool($verificare)) {
                                    echo printError($verificare);
                                } elseif (!$databFull->setNewUserPassword($user_info->idpers, $_POST['parolaNoua1'])) {
                                    echo $page->printDialog('danger', 'Nu s-a putut updata utilizatorul!');
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
                $errori = $databFull->getErrorsByPers($id_persoana, $session->getOperator());
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
                        
                    </div>
                    <?php
                        /********************************************************************************************************************
                        |    LOCATII RESPONSABIL                                                                                            |
                        ********************************************************************************************************************/
                    ?>
                    <div class="panel-body" style="padding:0px;" id="locatiiPanel_locatii">
                        
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
        <div id='locatieTarget' style="padding-left: 0px; width: 80%"   class="col-md-<?php echo ($session->getGrad() == 0 OR $session->getGrad() == 3) ? 9 : 12 ?>  col-sm-<?php echo ($session->getGrad() == 0 OR $session->getGrad() == 3) ? 9 : 12; ?>">
           
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                <?php
                    // if (isset($_SESSION['locatii_tip'])) {
                ?>
                        main.show_locatii_total_header('<?php echo $session->getIdresp();?>', '<?php echo $session->getOperator();?>','DESC',  'culoareAparat'); 
                        // main.show_locatii_total('<?php echo $session->getIdresp();?>', '<?php echo $session->getOperator();?>', 'DESC', 'culoareAparat');  
                <?php
                    // }
                ?>
            });

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