<?php
    require_once('../router.php');
    // require_once('../classes/SessionClass.php');
    // require_once('../includes/dbFull.php');
    // require_once('../classes/PageClass.php');
    error_reporting(0);
    $session = new SessionClass();
    if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
        header('location:index.php');
    }

    $page = new PageClass();
    $appSettings = $session = new SessionClass();
    $session->exchangeArray($_SESSION);
    $db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
    $page->checkLogin($session->getUsername(), $session->getOperator());
    $dbScrie = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
    $get = $db->sanitizePost($_GET);

    if (isset($_GET['id_pers'])) {
        $id_persoana = $_GET['id_pers'];
    } else {
        $id_persoana = $session->getIdresp();
    }
    if (isset($_POST['op'])) {
        $session->setOperator($_GET['op']);
    }
    if ($session->getGrad() != 0) {
        $id_locatie = $session->getIdLocatie();
    }
    $numarAparateAmpera = $db->getUserAparate('1',$session);
    $numarAparateRedlong = $db->getUserAparate('2',$session);
    $personalAmpera = $db->getLocatiiPersonal('1',$session);
    $personalRedLong = $db->getLocatiiPersonal('2',$session);
    $toateAparatele = $db->getStareAparate();
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
?>
<div class="panel-heading">
    <strong><span
            class="glyphicon  glyphicon glyphicon-user"></span><?php echo $personalAmpera[$session->getIdresp()]->nick; ?>
    </strong>
    <italic style="display:block;"><?php
        if ($session->getOperator() == 1) {
            echo 'A' . '(' . $personalAmpera[$session->getIdresp()]->nr_locatii . 'L / ' . $numarAparateAmpera[$personalAmpera[$session->getIdresp()]->nick]->nr_aparate . 'A / ' . $db->getAparateDepozitByResponsabil($personalAmpera[$session->getIdresp()]->idpers, 'depozita') . 'AD ) P(0/0)';
        } elseif ($session->getOperator() == 2) {
            echo 'R' . '(' . $personalRedLong[$session->getIdresp()]->nr_locatii . 'L / ' . $numarAparateRedlong[$personalRedLong[$session->getIdresp()]->nick]->nr_aparate . 'A / ' . $db->getAparateDepozitByResponsabil($personalRedLong[$session->getIdresp()]->idpers, 'depozitr') . 'AD ) P(0/0)';
        } else {
            echo 'T' . '(' . ($personalAmpera[$session->getIdresp()]->nr_locatii + $personalRedLong[$session->getIdresp()]->nr_locatii) . 'L / ' . ($numarAparateRedlong[$personalRedLong[$session->getIdresp()]->nick]->nr_aparate + $numarAparateAmpera[$personalAmpera[$session->getIdresp()]->nick]->nr_aparate) . 'A / ' . $db->getAparateDepozitByResponsabil($id_persoana, 'depozit') . 'AP ) P(0/0)';
        }
        ?>
        <input type="hidden" id="id_pers" value="<?php echo $session->getIdresp();?>"/>
    </italic>
    <span style="display: inline-block;width: 68%;">
        <input type="text" name="locatie" class="form-control" placeholder="Numele Locatiei"
               id="locatieAuto"/>
    </span>
    <span style='display: inline-block;width :32%; float:right; '>
        <a href="?id_pers=<?php echo $session->getIdresp() ?>&op=<?php echo $session->getOperator() ?>&sort=ASC"><img
                src='css/images/green_light.png' style='width:20px; height: 20px;'/></a>
        <a href="?id_pers=<?php echo $session->getIdresp() ?>&op=<?php echo $session->getOperator() ?>&sort=DESC"><img
                src='css/images/red_light.png' style='width:20px; height: 20px;'/></a>
        <?php 
            if ($locatiiCuErori != 0) { 
        ?>
                <a href="?id_pers=<?php echo $session->getIdresp() ?>&op=<?php echo $session->getOperator() ?>&sort=ERROR" >
                    <img src='css/images/triangle_red.png' style='width:20px; height: 20px;'/>
                </a>
        <?php
                echo $locatiiCuErori;
            }
        ?>
    </span>
</div>
<div class="panel-body" style="padding:0px;">
    <script>
        $(document).ready(function () {
            $('#locatieAuto').instaFilta();
            $("#tabs").tabs();
        });
    </script>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Aparate</a></li>
            <li><a href="#tabs-2">Locatii</a></li>
            <li><a href="#tabs-3">Error</a></li>
        </ul>

        <div id="tabs-1" style="padding:0px">
            <ul class="list-group">
                <?php
                    $locatii = $db->getLocatii($session->getOperator(), $session->getIdresp(), $an, $luna, $sort);
                    $i = 1;

                    foreach ($locatii as $objLocatii) {
                        foreach ($objLocatii as $key => $value) {
                            if (isset($_GET['id_locatie'])) {
                                $id_locatie = $_GET['id_locatie'];
                            } elseif ($i == 1) {
                                $id_locatie = $value->idlocatie;
                            }
                            $operator = $db->getNumeOperatorLocatie($value->idlocatie);
                            if ($i == 1) {
                                $operator_init = $operator;
                                echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                            } else {
                                if ($operator_init != $operator) {
                                    $operator_init = $operator;
                                    echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                                } 
                            }

                            $first = ($i == 1) ? 'first': '';
                ?>

                            <li class="list-group-item instafilta-target <?php echo $first;?>"
                                style="display :inline-block; width: 100%; max-height: 300px; padding: 0px 0px;">
                                    <span class="pull-left">
                                        <a class="getLocatie2" data-luna="<?php echo $luna ?>"
                                           data-an="<?php echo $an ?>"
                                           id="<?php echo $session->getIdLocatie() == $value->idlocatie ? 'selected' : ''; ?>"
                                           href="#" data-pers="<?php echo $session->getIdresp() ?>"
                                           data-locatie="<?php echo $value->idlocatie; ?>">
                <?php
                                            echo $i . '. ' . $page->maxText($value->denumire, 15);
                                            $i+1;
                ?>
                                        </a>
                                    </span>
                                    <span class="pull-right">
                                        <span>
                <?php
                                                if (isset($aparatePerLocatie[$value->idlocatie])) {
                                                    $u = 1;
                                                    foreach ($aparatePerLocatie[$value->idlocatie] as $cheie => $valoare) {
                                                        $diferente = explode(',', $valoare);
                ?>
                                                        <span class="<?php echo ($diferente[0] < 1 AND $diferente[1] < 1 AND $diferente[2] < 1 AND $diferente[3] < 1) ? 'circle-green' : 'circle-red'; ?>">
                                                            <?php echo ($diferente[0] == 0) ? "00" : $diferente[0]; ?>
                                                        </span>
                <?php
                                                    }
                                                }
                ?>
                                        </span>
                                        <?php echo $key; ?>
                                        AP
                                    </span>
                            </li>
                <?php
                            $i++;  
                        }
                    }
                ?>
            </ul>
        </div>
        <div id="tabs-2" style="padding: 0px;">
            <ul class="list-group">
                <?php
                    $locatii = $db->getLocatii($session->getOperator(), $session->getIdresp(), $an, $luna);
                    $i = 1;
                    foreach ($locatii as $objLocatii) {
                        foreach ($objLocatii as $key => $value) {
                            if (isset($_GET['id_locatie'])) {
                                $id_locatie = $_GET['id_locatie'];
                            } elseif ($i == 1) {
                                $id_locatie = $value->idlocatie;
                            }
                ?>
                            <li class="list-group-item instafilta-target" style="max-height: 300px; min-height: 40px;">
                                    <span class="pull-left">
                                        <a href="?id_pers=<?php echo $id_persoana; ?>&id_locatie=<?php echo $value->idlocatie; ?>">
                <?php
                                            echo $i . '. ' . $page->maxText($value->denumire, 15);
                                            $i++;
                ?>
                                        </a>
                                    </span>
                                    <span class="pull-right"> <?php echo $key; ?> AP </span>
                            </li>
                <?php
                        }
                    }
                ?>
            </ul>

        </div>
        <div id="tabs-3" style="padding:0px; display : block;">
            <ul class="list-group">
                <?php
                    $locatii = $db->getLocatii($session->getOperator(), $id_persoana, $an, $luna, $sort);
                    $i = 1;
                    foreach ($locatii as $objLocatii) {
                        foreach ($objLocatii as $key => $value) {
                            if (isset($_GET['id_locatie'])) {
                                $id_locatie = $_GET['id_locatie'];
                            } elseif ($i == 1) {
                                $id_locatie = $value->idlocatie;
                            }
                            if (isset($aparatePerLocatie[$value->idlocatie])) {
                                if (count($aparatePerLocatie[$value->idlocatie]) > 10) {
                                    $height = 100;
                                } else {
                                    $height = 60;
                                }
                            }
                ?>

                            <li class="list-group-item instafilta-target"
                                style="display :inline-block; width: 100%; max-height: 300px;">
                                <span class="pull-left">
                                    <a href="?id_pers=<?php echo $id_persoana; ?>&id_locatie=<?php echo $value->idlocatie; ?>"><?php
                                        echo $i . '. ' . $page->maxText($value->denumire, 15);
                                        $i++;
                ?>
                                    </a>
                                </span>
                                <span class="pull-right">
                                    <span>
                <?php
                                        foreach ($errori as $cheie => $valoare) {
                                            if ($value->idlocatie == $cheie) {
                                                foreach ($valoare as $ch => $val) {
                ?>
                                                    <img width="10px" height="10px"
                                                         data-idAparat="<?php echo $ch; ?>"
                                                         class="eroriPic"
                                                         src="css/images/<?php echo $val == 0 ? 'triangle_blue.png' : 'triangle_red.png' ?>"/>
                <?php
                                                }
                                            }
                                        }
                ?>
                                    </span>
                                    <?php echo $key; ?> AP
                                </span>
                            </li>
                <?php
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</div>