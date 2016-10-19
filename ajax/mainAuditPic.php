<?php
require_once "../autoloader.php";
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PicClass.php');
require_once('../classes/PageClass.php');
error_reporting(0);
$session = new SessionClass();
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$page = new PageClass();
$database = new DataConnection();

$macPicMapper = new MacPicMapper($database, $session);
$stareAparateMapper = new StareAparateMapper($database, $session);
$stare = $stareAparateMapper->getStareAparatByIdAparat(intval($_POST['id']));

$bitiComanda['11'] = 1;
// $bitiComanda['31'] = $stare->getIdAparat();   	//	ID Aparat
// $bitiComanda['30'] = $stare->getLastIdxInM();  	//	Contor Mecanic IN
// $bitiComanda['29'] = $stare->getLastIdxOutM();  //	Contor Mecanic OUT
// $bitiComanda['28'] = $stare->getLastIdxBetM();   	//	Contor Mecanic TotalBet
// $bitiComanda['27'] = 1;   						//	Trimite daca se modifica idxIn
// $bitiComanda['26'] = 1;  						//	Trimite daca se modifica idxOut

// $bitiComanda['23'] = $stare->getTimpOff();	//	Contor Mecanic Timp Off
// $bitiComanda['22'] = $stare->getTimpPachet1();	//	Durata Pachet 1
// $bitiComanda['21'] = $stare->getTimpPachet2();	//	Durata Pachet 2

// $bitiComanda['17'] = $stare->getHostNamePic();	//	Host Name
// $bitiComanda['16'] = $stare->getUserPic();	//	User Name
// $bitiComanda['15'] = $stare->getPassPic();	//	Password
$count = 1;
$data_curenta = date('d-m-Y H:i:s');
$date_start = time();
$interog = "SELECT ultimaConectare FROM {$db->getDatabase()}.stareaparate WHERE stareaparate.idAparat=".intval($_POST['id']);
$status = $db->query($interog);
$data = $status->fetch_object();
$ultimaConectare = $data->ultimaConectare;

$counter = 1;
$query = "UPDATE {$db->getDatabase()}.stareaparate SET  stareRetur='1', bitiComanda='2048' WHERE idAparat=".intval($_POST['id']);

$err = 1;
while ( $count <= 8) {
    $interog = "SELECT ultimaConectare FROM {$db->getDatabase()}.stareaparate WHERE stareaparate.idAparat=".intval($_POST['id']);
    $status = $db->query($interog);
    $data = $status->fetch_object();
    $ultimaConectare2 = $data->ultimaConectare;
    $data_curenta2 = date('d-m-Y H:i:s');
    // echo $count.' - ultimaConectare1='.$ultimaConectare.'<br />start='.$data_curenta.'<br />end='.$data_curenta2.'<br />ultimaConectare2='.$ultimaConectare2.'<br />';
    if ($ultimaConectare <  $ultimaConectare2) {
        $err = 0;
        break;
    }
    sleep(1);
    $count++;
}   
$date_elapsed = time() - $date_start;             
if ($err == 0) {
    $mesaj = 'Auditul s-a realizat cu succes in '.$date_elapsed.' secunde!';
} else {
    $mesaj = 'Nu s-a realizat auditul!';
}
echo $mesaj;
?>