<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
$appSettings = $session = new SessionClass();
$page = new PageClass();
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Aparate cu si fara picuri</title>
        <?php require_once('includes/header.php'); ?>
    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div class='container-fluid'>
            <div class='col-md-12'>
                <div class='panel panel-primary'>
                    <div class='panel-heading'>
                        Lista aparate
                        <div class="pull-right">Aparate cu pic : <span id="cuPic"></span></div>
                        <div class="pull-right" style="margin-right: 15px;">Aparate fara pic <span id="faraPic"></span></div>
                        <div class="pull-right" style="margin-right: 20px;">Aparate ce au transmis in ultima ora  : <?php echo $db->getAparateUltimaOra() ?></div>
                    </div>
                    <div class='panel-body'>
                        <table class='table table-bordered table-responsive table-striped'>
                            <thead>
                                <tr>
                                    <th>Nr</th>
                                    <th>Responsabil</th>
                                    <th>Locatie</th>
                                    <th>Seria</th>
                                    <th>V</th>
                                    <th>Index In</th>
                                    <th>Index Out</th>
                                    <th>Ip WAN</th>
                                    <th>Ip 3g</th>
                                    <th>Ultima Conctare</th>
                                    <th>Op</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cuPic = $faraPic = 0;
                                $aparate = $db->getAparateCuPic();
                                $responsabil = '';
                                $denLoc = '';
                                $i = 1;
                                foreach ($aparate as $aparat) {
                                    ?>
                                    <tr class="
                                    <?php
                                    if ($aparat->ipPic == '' AND $aparat->ipPic3g == '') {
                                        echo 'noPic';
                                        $faraPic += 1;
                                    }else{
                                        $cuPic +=1;
                                    }
                                    ?>">
                                        <td><?php echo $i;
                                    $i++; ?></td>
                                        <td>
                                            <?php
                                            if ($responsabil != $aparat->nick) {
                                                echo $aparat->nick;
                                                $responsabil = $aparat->nick;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($denLoc != $aparat->denumire) {
                                                echo $aparat->denumire;
                                                $denLoc = $aparat->denumire;
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $aparat->seria; ?></td>
                                        <td><?php echo $aparat->verSoft; ?></td>
                                        <td><?php echo $aparat->lastIdxInM; ?></td>
                                        <td><?php echo $aparat->lastIdxOutM ?></td>
                                        <td><?php echo $aparat->ipPic; ?></td>
                                        <td><?php echo $aparat->ipPic3g; ?></td>
                                        <td><?php echo $aparat->ultimaConectare ?></td>
                                        <td><?php echo ($aparat->idOperator == 1) ? 'A' : 'R' ?></td>
                                    </tr>
<?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <script type='text/javascript'>
        $(document).ready(function () {
            var cuPic = <?php echo $cuPic ?>;
            var faraPic = <?php echo $faraPic ?>;
            $('#cuPic').html(cuPic);
            $('#faraPic').html(faraPic);
        });
    </script>
</body>
</html>