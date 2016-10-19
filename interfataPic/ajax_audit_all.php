<?php
    
    require_once "../autoloader.php";
    error_reporting(0);
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

    $idAparat = intval($_POST[idAparat]);
    $data_curenta = date('d-m-Y H:i:s');

    $row = $datab->getRows('stareaparate', 'bitiComanda, ultimaConectare', 'WHERE idAparat=?', array($idAparat));
    $bitiComanda = $row[0][bitiComanda];
    $ultimaConectare = $row[0][ultimaConectare];
    $date_start = time();
    $array_update = array('2048', 1,  date('Y-m-d H:i:s'), $idAparat);
    $updated = $datab->updateRow('stareaparate', 'bitiComanda=?, stareRetur=?, timpAudit=?', 'WHERE idAparat=?', $array_update);
    // sleep(3);
    $count = 1; // sec
    $err = 1;
    while ( $count <= 12) {
        sleep(1);
        $data_curenta2 = date('d-m-Y H:i:s');
        $row = $datab->getRows('stareaparate', 'ultimaConectare', 'WHERE idAparat=?', array($idAparat));
        $ultima_conectare2 = $row[0][ultimaConectare];
        
        // echo 'ultimaConectare1='.$ultimaConectare.'<br />start='.$data_curenta.'<br />end='.$data_curenta2.'<br />ultimaConectare2='.$ultima_conectare2;
        // $date_ultima_conectare = time() - strtotime($ultima_conectare);
        $date_elapsed = time() - $date_start;
        // echo $count.' - ultimaConectare1='.$ultimaConectare.'<br />start='.$data_curenta.'<br />end='.$data_curenta2.'<br />ultimaConectare2='.$ultima_conectare2.'<br /> - '.$date_elapsed.' s<br />----------------------------------------<br />';
        if ($ultimaConectare <  $ultima_conectare2) {
            $err = 0;
            break;
        }
        
        $count++;
    }                
    if ($err == 0) {
    	$mesaj = '<span style="color: green">Auditul s-a realizat cu succes !';
        $mesaj2 = 'Auditul s-a realizat cu succes !';
        $datab->logsInsertRow($_SESSION['username'], 'UPDATE', 'stareaparate', 'bitiComanda,stareRetur,timpAudit', $array_update, 'WHERE idAparat='.intval($idAparat));
    } else {
    	$mesaj = '<span style="color: red"> Nu s-a realizat auditul!</span>';
        $mesaj2 = 'Nu s-a realizat auditul!';
    }
    $dateStare = $datab->getRows('stareaparate', 'verSoft, macPic, lastIdxInM, lastIdxOutM, lastIdxBetM, lastIdxInE, lastIdxOutE, lastIdxBetE', 'WHERE idAparat=?', $array=array($aparat->getIdAparat()));
    $data = array(
                    'mesaj' => $mesaj, 
                    'err' => $err, 
                    'date_elapsed' => $date_elapsed, 
                    'date' => 
                                array(
                                    'verSoft' => $dateStare[0][verSoft],
                                    'macPic' => $dateStare[0][macPic],
                                    'idxInMRet' => $dateStare[0][lastIdxInM],
                                    'idxOutMRet' => $dateStare[0][lastIdxOutM],
                                    'idxBetMRet' => $dateStare[0][lastIdxBetM],
                                    'lastIdxInE' => $dateStare[0][lastIdxInE],
                                    'lastIdxOutE' => $dateStare[0][lastIdxOutE],
                                    'lastIdxBetE' => $dateStare[0][lastIdxBetE]
                                )
                            
            );
    header('Content-Type: application/json');
    echo json_encode($data);
?>