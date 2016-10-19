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
    /**
     * UPDATE idApRetur pentru aparat cu idAparat VECHI
     */
        $idAparat = intval($_POST[idAparat]);
        $serieAparat = $_POST[serieAparat];
        $data_curenta = date('d-m-Y H:i:s');
     
        $row = $datab->getRows('aparate', 'idAparat', 'WHERE seria=? ORDER BY idAparat DESC LIMIT 2', array($serieAparat));
        $idAparat2 = $row[1][idAparat];
        
        $updated_idApRetur = $datab->updateRow('stareaparate', 'bitiComanda=?, stareRetur=?, idApRetur=?', 'WHERE idAparat=?', array('2147483648', 1, $idAparat, $idAparat2));
        if ($updated_idApRetur) {
            $out = 'idApRetur updatat cu succes!';
        } else {
            $out = 'idApRetur nu s-a modificat!';
        }

    /**
     * AUDIT APARAT Cu idAparat NOU
     */
        // $row = $datab->getRows('stareaparate', 'bitiComanda, ultimaConectare', 'WHERE idAparat=?', array($idAparat));
        // $bitiComanda = $row[0][bitiComanda];
        // $ultimaConectare = $row[0][ultimaConectare];
        // $date_start = time();
        
        // $updated_audit = $datab->updateRow('stareaparate', 'bitiComanda=?, stareRetur=?, timpAudit=?', 'WHERE idAparat=?', array('2048', 1,  date('Y-m-d H:i:s'), $idAparat));
        // $count = 1; // sec
        // $err = 1;
        // while ( $count <= 12) {
        //     sleep(1);
        //     $data_curenta2 = date('d-m-Y H:i:s');
        //     $row = $datab->getRows('stareaparate', 'ultimaConectare', 'WHERE idAparat=?', array($idAparat));
        //     $ultima_conectare2 = $row[0][ultimaConectare];
            
        //     // echo 'ultimaConectare1='.$ultimaConectare.'<br />start='.$data_curenta.'<br />end='.$data_curenta2.'<br />
        //     $date_elapsed = time() - $date_start;
        //     echo $count.' - ultimaConectare1='.$ultimaConectare.'<br />start='.$data_curenta.'<br />end='.$data_curenta2.'<br />ultimaConectare2='.$ultima_conectare2.'<br /> - '.$date_elapsed.' s<br />----------------------------------------<br />';
        //     if ($ultimaConectare <  $ultima_conectare2) {
        //         $err = 0;
        //         break;
        //     }
            
        //     $count++;
        // }                
        // if ($err == 0 && $updated_idApRetur) {
        //     $mesaj = 'Auditul s-a realizat cu succes!';
        // } else {
        //     $mesaj = 'Nu s-a realizat auditul!
        //     ';
        // }


    echo $out;
?>