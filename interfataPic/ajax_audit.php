<?php
    error_reporting(0);
    require_once "../autoloader.php";
    require_once('../includes/class.db.php');
    $appSettings = new SessionClass();
    $datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $page = new PageClass();
    $database = new DataConnection();
    $aparateMapper = new AparateMapper($database,$appSettings);
    $macPicMapper = new MacPicMapper($database,$appSettings);
    $locatiiMapper = new LocatiiMaper($database,$appSettings);
    $aparat = $aparateMapper->getAparat($_POST['idAparat']);
    $locatie  = $locatiiMapper->getLocatie($aparat->getIdLocatie());

    $row = $datab->getRows('stareaparate', 'bitiComanda, ultimaConectare', 'WHERE idAparat=?', array($idAparat));
    $bitiComanda = $row[0][bitiComanda];
    $ultimaConectare = $row[0][ultimaConectare];

    $date_start = time();
    $idAparat = intval($_POST[idAparat]);

    $biti = $page->getBinariFromDecimal($bitiComanda);
    $biti[11] = 1;
    $bitiComanda = $macPicMapper->getStringComanda($biti);

    $steps = 1;
    $statusul='';
    $stat = 1;
    $data_curenta = date('d-m-Y H:i:s');
    $updated = $datab->updateRow('stareaparate', 'bitiComanda=?, stareRetur=?', 'WHERE idAparat=?', array($bitiComanda, 1, $idAparat));
   	//$query = "UPDATE stareaparate SET bitiComanda=$bitiComanda, stareRetur='1' WHERE idAparat='{$idAparat}'";
    // $db->query($query);
    sleep(1);

    $row = $datab->getRows('stareaparate', 'ultimaConectare', 'WHERE idAparat=?', array($idAparat));
    $ultima_conectare2 = $row[0][ultimaConectare];


    // echo date('d-m-Y H:i:s', strtotime($ultima_conectare)).'- '.$data_curenta;

    $date_ultima_conectare = time() - strtotime($ultima_conectare);
    $date_elapsed = time() - $date_start;
    if ($$ultimaConectare <  $ultima_conectare2) {
    // if ($date_ultima_conectare <= 3) {
    	$mesaj = '<span style="font-size: 20px; color: green">Auditul s-a realizat cu succes !';
    	$err = 0;
    } else {
    	// echo '<span style="font-size: 16px; color: red"> Nu s-a realizat auditul!</span>*'.$date_elapsed.'s*';
    	$mesaj = '<span style="font-size: 16px; color: red"> Nu s-a realizat auditul!</span>';
    	$err = 1;
    }
    header('Content-Type: application/json');
    echo json_encode(array('err' => $err, 'mesaj' => $mesaj, 'timp'=>$date_elapsed.'s'))
?>