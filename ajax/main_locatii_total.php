<?php
    require_once('../autoloader.php');
    // error_reporting(E_ALL);
    $_SESSION['locatii_tip'] = 'T';
    require_once "../includes/class.db.php";
    require_once "../includes/class.databFull.php";
    $page = new PageClass();
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $idresp = intval($_POST['idresp']);
    $operator = intval($_POST['operator']);
    if (!isset($_POST['luna']) OR !isset($_POST['an'])) {
        $luna = date('n');
        $an = date('Y');
    } else {
        $luna = $_POST['luna'];
        $an = $_POST['an'];
    }
?>

<div class="panel-body" style="padding:0px;">
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
                <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="display :inline-block; width: 100%; max-height: 300px; padding: 8px 18px;">
                    
                        <a style='font-size: 13px' class="getLocatie2" data-luna="<?php echo $luna ?>"
                           data-an="<?php echo $an ?>"
                           data-op="1"
                           id="<?php echo $_SESSION['idLocatie'] == 792 ? 'selected' : ''; ?>"
                           href="#" data-pers="5"
                           data-locatie="792"><span class="pull-left">
            <?php
                                    echo 'TEST';
                                    $nrAparate = $databFull->getAparateByPers(792);

            ?>
                        </span></a>
                    
            <?php
                     echo '&nbsp;&nbsp;<span class="pull-right"> <span>'.count($nrAparate).' AP'.'</span></span>';
            ?>
                </li>
            <?php
                // $locatii = $db->getLocatii($session->getOperator(), $idresp, $an, $luna, $sort);
                $totalAparateResponsabil = 0;
                $i = 1;
                $type = (isset($_POST['type'])) ? $_POST['type'] : 'culoareAparat';
                $sort = (isset($_POST['sort'])) ? $_POST['sort'] : 'DESC';
                $tip_sortare = 'ord';
                
                if (isset($_POST['operator']) && $_POST['operator'] != 0 ) {
                    $operatori = array($_POST['operator']);
                } else {
                    $operatori = array(1, 2);
                }
                $class_first = '';
                // echo $idOperator.' - '.$idresp.' - '.$an.' - '.$luna.' - '.$type.' - '.$sort.' - '.$tip_sortare;
                foreach ($operatori as $key => $idOperator) {
                    $rows_locatii[$idOperator] = $databFull->getLocatiiResponsabil($idOperator, $idresp, $an, $luna, $type, $sort, $tip_sortare);
                    foreach ($rows_locatii[$idOperator] as $key => $value) {
                        $id_locatie = $value['idlocatie'];
                        $denumireLocatie = $value['denumire'];
                        $nrAparate = $value['nrAparate'];
                        $culoareAparat = $value['culoareAparat'];
                        $idOperator = $value['idOperator'];
                        $aparateLocatie = $value['aparate'];
                        $stare_aparate = $value['stare_aparate'];
                        $nrAparateLocatie = count($aparateLocatie);
                        $operatorul = $databFull->getNumeOperatorLocatie($value['idlocatie']);
                        if ($i == 1) {
                            $operator_init = $operatorul;
                            echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                            echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                            

                            $class_first = 'first activated';
                        } else {
                            if ($operator_init != $operatorul) {
                                $operator_init = $operatorul;
                                echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                                echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                                $i = 1;
                            }
                            $class_first = '';
                        }
            ?>

                        <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="display :inline-block; width: 100%; max-height: 300px; padding: 0px 0px;">
                            
                                <a style='font-size: 13px'  class="getLocatie2" data-luna="<?php echo $luna ?>"
                                   data-an="<?php echo $an ?>"
                                   data-op="<?php echo $idOperator ?>"
                                   id="<?php echo $_SESSION['idLocatie'] == $id_locatie ? 'selected' : ''; ?>"
                                   href="#" data-pers="<?php echo $idresp ?>"
                                   data-locatie="<?php echo $id_locatie; ?>"><span class="pull-left">
            <?php
                                    echo $i . '. ' . $page->maxText($denumireLocatie, 10);
                                    $i++;
            ?>
                                </span></a>
                            
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
                }
                
            ?>
            </ul>
        </div>
        <?php
            /*------------------------------------------------------------------------------------------------------------------
            |    NUMAR APARATE                                                                                                 |
            ------------------------------------------------------------------------------------------------------------------*/
        ?>
        <div id="tabs-2" style="padding: 0px;">
            <ul class="list-group">
                <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="display :inline-block; width: 100%; max-height: 300px; padding: 8px 18px;">
                    
                        <a style='font-size: 13px'  class="getLocatie2" data-luna="<?php echo $luna ?>"
                           data-an="<?php echo $an ?>"
                           data-op="1"
                           id="<?php echo $_SESSION['idLocatie'] == 792 ? 'selected' : ''; ?>"
                           href="#" data-pers="5"
                           data-locatie="792"><span class="pull-left">
            <?php
                                    echo 'TEST';
                                    $nrAparate = $databFull->getAparateByPers(792);

            ?>
                        </span></a>
                    
            <?php
                     echo '&nbsp;&nbsp;<span class="pull-right"> <span>'.count($nrAparate).' AP'.'</span></span>';
            ?>
                </li>
                <?php
                    $i = 1;
                    $class_first = '';
                    foreach ($operatori as $key => $idOperator) {
                        foreach ($rows_locatii[$idOperator] as $key => $value) {
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
                                echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                                echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                                $class_first = 'first activated';
                            } else {
                                if ($operator_init != $operator) {
                                    $operator_init = $operator;
                                    echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                                    echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                                    $i = 1;
                                }
                                $class_first = '';
                            }
                ?>
                            <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="max-height: 300px; min-height: 40px;">
                                
                                    <a  style='font-size: 13px' class="getLocatie2" data-luna="<?php echo $luna ?>"
                                       data-an="<?php echo $an ?>"
                                       data-op="<?php echo $idOperator ?>"
                                       id="<?php echo $_SESSION['idLocatie'] == $id_locatie ? 'selected' : ''; ?>"
                                       href="#" data-pers="<?php echo $idresp ?>"
                                       data-locatie="<?php echo $id_locatie; ?>"><span class="pull-left">
                <?php
                                        echo $i . '. ' . $page->maxText($denumireLocatie, 10);
                                        $i++;
                ?>
                                     </span></a>
                               
                                <span class="pull-right"> <?php echo $nrAparateLocatie; ?> AP</span>
                            </li>
                <?php
                        }
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
                <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="display :inline-block; width: 100%; max-height: 300px; padding: 8px 18px;">
                    
                        <a style='font-size: 13px'  class="getLocatie2" data-luna="<?php echo $luna ?>"
                           data-an="<?php echo $an ?>"
                           data-op="1"
                           id="<?php echo $_SESSION['idLocatie'] == 792 ? 'selected' : ''; ?>"
                           href="#" data-pers="5"
                           data-locatie="792"><span class="pull-left">
            <?php
                                    echo 'TEST';
                                    $nrAparate = $databFull->getAparateByPers(792);

            ?>
                        </span></a>
                    
            <?php
                     echo '&nbsp;&nbsp;<span class="pull-right"> <span>'.count($nrAparate).' AP'.'</span></span>';
            ?>
                </li>
                <?php
                    $i = 1;
                    $class_first = '';
                    foreach ($operatori as $key => $idOperator) {
                        foreach ($rows_locatii[$idOperator] as $key => $value) {
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
                                echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                                echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                                $class_first = 'first activated';
                            } else {
                                if ($operator_init != $operator) {
                                    $operator_init = $operator;
                                    echo "<li class='list-group-item' style='padding: 10px 0px;'><span style='color: #F57900'> {$operator_init}</span>";
                                    echo "<div class='locatie-detalii'>".$databFull->getDateOperatorResp_aparateLocatii($idOperator, $idresp, $an, $luna).'</div></li>';
                                    $i = 1;
                                }
                                $class_first = '';
                            }
                ?>

                            <li class="list-group-item instafilta-target <?php echo $class_first;?>" style="display :inline-block; width: 100%; max-height: 300px;">
                               
                                    <a style='font-size: 13px'  class="getLocatie2" data-luna="<?php echo $luna ?>"
                                       data-an="<?php echo $an ?>"
                                       data-op="<?php echo $idOperator ?>"
                                       id="<?php echo $_SESSION['idLocatie'] == $id_locatie ? 'selected' : ''; ?>"
                                       href="#" data-pers="<?php echo $idresp ?>"
                                       data-locatie="<?php echo $id_locatie; ?>"> <span class="pull-left">
                <?php
                                        echo $i . '. ' . $page->maxText($denumireLocatie, 10);
                                        $i++;
                ?>
                                    </span></a>
                                
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
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
<style>
    li.activated {
        background: #D9EDF7;
        color: #000;
    }
    .ui-widget-content a.getLocatie2 {
        font-style: 14px !important;
    }
    .locatie-detalii italic {
        font-size: 12px !important;
        margin-top: 7px;
    }
</style>