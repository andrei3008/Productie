<?php
require_once "autoloader.php";
// error_reporting(E_ALL);
require_once('classes/SessionClass.php');
require_once 'includes/dbFull.php';
require_once 'classes/Logger.php';
require_once ('classes/PageClass.php');
$session =  $appSettings = new SessionClass();
$page = new PageClass();
if (!isset($_SESSION['username']) AND ! isset($_SESSION['operator'])) {
    header('location:index.php');
}
if (isset($_GET['zi'])) {
    $zi = $_GET['zi'];
} else {
    $zi = date('j');
}
if (isset($_GET['luna'])) {
    $luna = $_GET['luna'];
} else {
    $luna = date('n');
}
if (isset($_GET['an'])) {
    $an = $_GET['an'];
} else {
    $an = date('Y');
}
$href = array(
    'zi' => $zi,
    'luna' => $luna,
    'an' => $an
);
$file_log_Amp = 'minister/logs/logs' . ($an . '-' . (($luna < 10) ? '0' . $luna : $luna) . '-' . (($zi < 10) ? '0' . $zi : $zi)) . '.txt';
$loggerRed = new Logger('../redlong/minister/logs/logs' . ($an . '-' . (($luna < 10) ? '0' . $luna : $luna) . '-' . (($zi < 10) ? '0' . $zi : $zi)) . '.txt');
$loggerAmp = new Logger('minister/logs/logs' . ($an . '-' . (($luna < 10) ? '0' . $luna : $luna) . '-' . (($zi < 10) ? '0' . $zi : $zi)) . '.txt');
?>
<!DOCTYPE>
<html>
    <head>
        <title>Logs</title>
        <?php require_once 'includes/header.php'; ?>
        <script type="text/javascript" src="js/jquery.stopwatch.js"></script>
    </head>
    <body>
        <input type="hidden" name="anCurentLog" id="anCurentLog" value="<?php echo $an; ?>"/>
        <input type="hidden" name="lunaCurentLog" id="lunaCurentLog" value="<?php echo $luna ?>"/>
        <?php
        require_once 'includes/menu.php';
        $page = new PageClass();
        $page->setLocale();
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div id="zile">
                            <?php
                            $ani = array(
                                $an    => $an,
                                '2015' => '2015',
                                '2016' => '2016');
                            $luni = $page->getLuniArray();
                            $luni = array($luna=>  strftime('%B',  strtotime($an."-".$luna.'-'.$zi)))+$luni;
                            echo 'Anul : ' . $page->createSelectElement('an', 'id="anLog" class="pagination-center black"', $ani);
                            echo 'Luna : ' . $page->createSelectElement('luna', 'id="lunaLog" class="pagination-center black"', $luni);
                            echo '<br/>';
                            echo $page->createDayPicker($zi, $luna, $an, 'class="pagination pagination-sm"', $href);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">Loguri</div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>IP</th>
                                    <th>Ora Login</th>
                                    <th>Ora Logout</th>
                                    <th>Durata</th>
                                    <th>Firma</th>
                                    <th>Observatii</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ipCunoscute = [
                                    '82.79.220.114'     => 'bruner',
                                    '62.231.98.114'    => 'Adi Munca',
                                    '79.117.152.45'    =>'Adi Acasa'
                                ];
                                $loguriAmpera = $loggerAmp->getAll();
                                $i=1;
                                foreach ($loguriAmpera as $log) {
                                    ?>
                                    <tr>
                                        <td><?php if(array_key_exists(trim($log['ip']),$ipCunoscute)){
                                                echo $ipCunoscute[trim($log['ip'])];
                                            }else{
                                                echo $log['ip'];
                                            } ?></td>
                                        <td><?php echo $log['ora-login']; ?></td>
                                        <td><?php echo $log['ora-logout']; ?></td>
                                        <td <?php echo ($log['ora-logout'] == 0) ? 'style="background-color : lightgrey"' : ''; ?>><?php
                                            if($log['ora-logout']!=0) {
                                               echo $log['durata-sesiune'];
                                            }else{
                                                ?>
                                                <span id="demo<?php echo $i; ?>"></span>
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        $('#demo<?php echo $i; $i++; ?>').stopwatch({startTime: <?php echo $log['durata-sesiune'] ?>}).stopwatch('start');
                                                    })
                                                </script>
                                                <?php
                                            }
                                            ?></td>
                                        <td>Ampera</td>
                                        <td>-</td>
                                    </tr>
                                    <?php
                                }
                                $loguriRedlong = $loggerRed->getAll();
                                foreach ($loguriRedlong as $log) {
                                    ?>
                                    <tr>
                                        <td><?php if(array_key_exists(trim($log['ip']),$ipCunoscute)){
                                                echo $ipCunoscute[trim($log['ip'])];
                                            }else{
                                                echo $log['ip'];
                                            } ?></td>
                                        <td><?php echo $log['ora-login']; ?></td>
                                        <td><?php echo $log['ora-logout']; ?></td>
                                        <td <?php echo ($log['ora-logout'] == 0) ? 'style="background-color : lightgrey"' : ''; ?>><?php
                                            if($log['ora-logout']!=0) {
                                               echo $log['durata-sesiune'];
                                            }else{
                                                ?>
                                                <span id="demo<?php echo $i; ?>"></span>
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        $('#demo<?php echo $i; $i++; ?>').stopwatch({startTime:<?php echo $log['durata-sesiune'] ?>}).stopwatch('start');
                                                    })
                                                </script>
                                                <?php
                                            }
                                            ?></td>
                                        <td>Redlong</td>
                                        <td>-</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php ?>
    </body>
</html>