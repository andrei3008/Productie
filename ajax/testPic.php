<?php
require_once "../autoloader.php";
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PicClass.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$page = new PageClass();
$database = new DataConnection();

$macPicMapper = new MacPicMapper($database, $session);
$stareAparateMapper = new StareAparateMapper($database, $session);
$stare = $stareAparateMapper->getStareAparatByIdAparat(intval($_POST['id']));

/*
$fields = [
    'Perioada' => 1
];

$pic = new PicClass($_POST['ip'],$_POST['port'],$fields);
$result = $pic->connect();

$fields= $db->sanitizePost($fields);
$post = $db->sanitizePost($_POST);

$postFields = '';
foreach ($fields as $key => $value) {
    $postFields.="$key=$value,";
}


sleep(2);

if($result){
    echo 'Auditul a avut success!';
}else{
    echo 'Conexiunea la pic nu a putut fi initiata!';
}

$fields = [
    'Perioada' => 360,
    'SOCMI' => '',
    'SOCMO' => ''
];

foreach ($fields as $key => $value) {
    $postFields.="$key=$value,";
}

$db->insertAudit($post['id'], $post['seria'], $post['user'], $postFields);
$pic2 = new PicClass($_POST['ip'],$_POST['port'],$fields);
$pic2->connect();
*/

// $item = $stare->getStareAparatByIdAparat(intval($_POST['id']));

/*
    $bitiComanda['11'] = 1;
    $bitiComanda['31'] = $stare->getIdAparat();   	//	ID Aparat
    $bitiComanda['30'] = $stare->getLastIdxInM();  	//	Contor Mecanic IN
    $bitiComanda['29'] = $stare->getLastIdxOutM();  //	Contor Mecanic OUT
    $bitiComanda['28'] = $stare->getLastIdxBetM();   	//	Contor Mecanic TotalBet
    $bitiComanda['27'] = 1;   						//	Trimite daca se modifica idxIn
    $bitiComanda['26'] = 1;  						//	Trimite daca se modifica idxOut

    $bitiComanda['23'] = $stare->getTimpOff();	//	Contor Mecanic Timp Off
    $bitiComanda['22'] = $stare->getTimpPachet1();	//	Durata Pachet 1
    $bitiComanda['21'] = $stare->getTimpPachet2();	//	Durata Pachet 2

    $bitiComanda['17'] = $stare->getHostNamePic();	//	Host Name
    $bitiComanda['16'] = $stare->getUserPic();	//	User Name
    $bitiComanda['15'] = $stare->getPassPic();	//	Password

    $counter = 1;

    $query = "UPDATE {$db->getDatabase()}.stareaparate SET ";
    foreach ($bitiComanda as $nrBit => $value) {
        $counter++;
        $bitul = $page->getSemnificatieBiti($nrBit);
        if ($value != '') {
                $bitiComanda[$nrBit] = 1;
            if ($bitul['bazaDate'] != 'skip') {
                $query .= " {$bitul['bazaDate']}='{$value}' ";
                $query .= ',';
            }

        } else {
            $bitiComanda[$nrBit] = 0;
        }
    }
    $bitiComanda['11'] = 1;
    $stringComanda = $macPicMapper->getStringComanda($bitiComanda);
    $query .= " stareRetur='1', bitiComanda='{$stringComanda}' ";
    $query .= " WHERE idAparat='".intval($_POST['id'])."'";

    echo $stringComanda;
*/
$host = "86.122.183.194";
$port = 43568;
// don't timeout!
set_time_limit(0);
// create socket
// 


if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}
// if (socket_bind($sock, $host, $port) === false) {
//     echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }

// if (socket_listen($sock, 5) === false) {
//     echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
// }

// if ( ! socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1)) 
// { 
//     echo socket_strerror(socket_last_error($sock)); 
//     exit; 
// }


// $spawn = socket_accept($sock) or die("Could not accept incoming connection\n");
// $input = socket_read($spawn, 1024) or die("Could not read input\n");
// $input = trim($input);
// echo "Client Message : ".$input."<br />";
// 
// $socket = stream_socket_server("tcp://red77.ro:43568", $errno, $errstr);
// if (!$socket) {
//   echo "$errstr ($errno)<br />\n";
// } else {
//   while ($conn = stream_socket_accept($socket)) {
//     fwrite($conn, 'The local time is ' . date('n/j/Y g:i a') . "\n");
//     fclose($conn);
//   }
//   fclose($socket);
// }
?>