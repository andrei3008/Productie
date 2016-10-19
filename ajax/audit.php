<?php
error_reporting(0);
require_once "../autoloader.php";
require_once('../includes/class.db.php');
$appSettings = new SessionClass();
$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$database = new DataConnection();
$page = new PageClass();
$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
/*
 * Mappers
 */
$aparateMapper = new AparateMapper($database,$appSettings);
$macPicMapper = new MacPicMapper($database,$appSettings);
$locatiiMapper = new LocatiiMaper($database,$appSettings);
$aparat = $aparateMapper->getAparat($_POST['idAparat']);
$locatie  = $locatiiMapper->getLocatie($aparat->getIdLocatie());

if (!empty($_POST['audit'])) {
    $biti = $page->getBinariFromDecimal($aparat->getStareaparate()->getBitiComanda());
    $biti[11] = 1;
    $bitiComanda = $macPicMapper->getStringComanda($biti);
    
    $steps = 1;
    $statusul='';
    $stat = 1;
    
    /*
    for ($i=1; $i <= 1; $i++) { 
        $query = "UPDATE {$db->getDatabase()}.stareaparate SET bitiComanda=$bitiComanda, stareRetur='1' WHERE idAparat='{$aparat->getIdAparat()}'";
        $db->query($query);
        sleep(3);
        $interog = "SELECT stareRetur FROM {$db->getDatabase()}.ultimaConectare WHERE stareaparate.idAparat=".$aparat->getIdAparat();
        $status = $db->query($interog);
        $data = $status->fetch_object();
        $ultima_conectare = intval($data->ultimaConectare);
        // $statusuri = intval($data->stareRetur);
        // $statusul .= $statusuri;
        // if ($statusuri == 0) {
            $stat = 0;
            $date_ultima_conectare = time() - strtotime($ultima_conectare);
            $date_elapsed = time() - $date_start;
           
            // break;
        // }
        $stat = 1;
    }

    // echo '['.$statusul.']';
    if ($stat == 1) {
    	$date_elapsed = time() - $date_start;
        echo '<span style="color: red"> Nu s-a realizat auditul!</span>*timp:'.$date_elapsed.'secunde*';
        // echo "Auditul nu s-a putut realiza!" . $db->error;
    }
    echo '[Status: '.$statusul.']';
    */
   	// error_reporting(E_ALL)
   	// 
   
    $date_start = time();
   	$query = "UPDATE {$db->getDatabase()}.stareaparate SET bitiComanda=$bitiComanda, stareRetur='1' WHERE idAparat='{$aparat->getIdAparat()}'";
    $db->query($query);
    sleep(3);
    $interog = "SELECT ultimaConectare FROM {$db->getDatabase()}.stareaparate WHERE stareaparate.idAparat=".$aparat->getIdAparat();
    $status = $db->query($interog);
    $data = $status->fetch_object();
    $ultima_conectare = $data->ultimaConectare;
    $date_ultima_conectare = time() - strtotime($ultima_conectare);
    $date_elapsed = time() - $date_start;
    if ($date_ultima_conectare <= 3) {
    	echo '<span style="color: green">Auditul s-a realizat cu succes !</span>*'.$date_elapsed.'s*';
    } else {
    	echo '<span style="color: red"> Nu s-a realizat auditul!</span>*'.$date_elapsed.'s*';
    }
}
$dateStare = $datab->getRows('stareaparate', 'verSoft, macPic', 'WHERE idAparat=?', $array=array($aparat->getIdAparat()));
?>

<form name="CfgAparat" method="POST" id="configForm" class="formAudit">
    <div id="content_cfg" style="width:525px;">
        <div id="Tbl_02" style="position: relative">
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
                    <td><?php echo date('d M Y, H:i:s') ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Nume locatie :</th>
                    <td><?php echo $locatie->getDenumire() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>MAC :</th>
                    <td><?php echo $dateStare[0][macPic]; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Ver. Soft :</th>
                    <td><?php echo $dateStare[0][verSoft]; ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-aparat">
                    <th>Serie Aparat :</th>
                    <td><?php echo $aparat->getSeria(); ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-lot">
                    <th>ID Operator :</th>
                    <td><?php echo $locatie->getIdOperator() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-lot">
                    <th>Id Responsabil</th>
                    <td><?php echo $locatie->getIdresp() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-lot">
                    <th>ID Locatie :</th>
                    <td><?php echo $locatie->getIdlocatie() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-aparat">
                    <th>ID Aparat :</th>
                    <td><?php echo $aparat->getIdAparat() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-aparat">
                    <th>Contor Mecanic IN:</th>
                    <td><?php echo $aparat->getStareaparate()->getLastIdxInM() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-aparat">
                    <th>Contor Mecanic OUT:</th>
                    <td><?php echo $aparat->getStareaparate()->getLastIdxOutM() ?></td>
                    <td></td>
                </tr>
                <tr class="proprietate-aparat">
                    <th>Contor Mecanic TotalBet:</th>
                    <td><?php echo $aparat->getStareaparate()->getLastIdxBetM() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Contor Mecanic Timp Off :</th>
                    <td><?php echo $aparat->getStareaparate()->getTimpOff() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Durata Pachet 1:</th>
                    <td><?php echo $aparat->getStareaparate()->getTimpPachet1() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Durata Pachet 2:</th>
                    <td><?php echo $aparat->getStareaparate()->getTimpPachet2() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="3" align="center">Configurare Server</th>
                </tr>
                <tr>
                    <th>WebServer :</th>
                    <td><?php echo $aparat->getStareaparate()->getAdrPachet1() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Adresa Pachet 2 :</th>
                    <td><?php echo $aparat->getStareaparate()->getAdrPachet2(); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Adresa Pachet 3 :</th>
                    <td><?php echo $aparat->getStareaparate()->getAdrPachet3() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Host Name :</th>
                    <td><?php echo $aparat->getStareaparate()->getHostNamePic() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>User Name :</th>
                    <td><?php echo $aparat->getStareaparate()->getUserPic() ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Password :</th>
                    <td><?php echo $aparat->getStareaparate()->getPassPic() ?></td>
                    <td></td>
                </tr>

            </table>
            <div class="loading"><img src="../css/AjaxLoader.gif" /></div>
        </div>
    </div>
</form>
