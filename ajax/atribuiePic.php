<?php
/**
 * Pasi de urmat pentru imperechere pic
 * 1. Se preia ultimul aparat activ ce detine seria introdusa
 * 2. Se preia macul din baza de date
 * 3. Se verifica biti de comanda si daca bitul 31 din serie este 1 inseamna ca aparatul asteapta deja o configurare
 * deci se va genera o eroare
 * 4. Daca nu exista nici o eroare cu bitii de comanda se genereaza pachetul pentru configurarea aparatului
 * 5. Se updateaza stare aparate cu macul picului si biti de comanda
 * 6. Se sterge din tabela macpic macul curent.
 */

require_once "../autoloader.php";
require_once "../includes/class.db.php";
$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
$db = new DataConnection();
$appSettings = new SessionClass();

$aparateMapper = new AparateMapper($db, $appSettings);
$macsMapper = new MacPicMapper($db, $appSettings);
$page = new PageClass();
$stareAparateMapper = new StareAparateMapper($db,$appSettings);
/**
 * Pasul 1 Se preia ultimul aparat activ ce detine seria introdusa
 */
$aparat = $aparateMapper->getAparatBySerie($_POST['seria']);
$macDeAsociat = $_POST[macDeAsociat];
$idAparat = $_POST[idAparat];
/**
 * Pasul 2 Se preia macul din baza de date
 */
$mac = $macsMapper->getMacPic($_POST['idmac']);
$bitiComanda = $page->getBinariFromDecimal($aparat->getStareaparate()->getBitiComanda());

$row = $datab->getRows('stareaparate', 'idAparat', 'WHERE macPic=?', array($macDeAsociat));
$idAparatVechi = $row[0][idAparat];
/**
 * Pasul 4 
 * - vechiul aparat va avea mac NULL 
 * - salvez logul
 */

    $updated = $datab->updateRow('stareaparate', 'macPic=?', 'WHERE idAparat=?', array(NULL, $idAparatVechi));
    $datab->logsInsertRow($_SESSION['username'], 'UPDATE', 'stareaparate', 'macPic', array('NULL'), 'WHERE idAparat='.intval($idAparatVechi));

/**
 * Pasul 5 
 * - updatez macul, bitii de comanda, idApRetur si stareRetur
 * - salvez logul
 */

    $array_update = array('2147485696', 1, $macDeAsociat, $idAparat, $idAparat);
    $updated1 = $datab->updateRow('stareaparate', 'bitiComanda=?, stareRetur=?, macPic=?, idApRetur=?', 'WHERE idAparat=?', $array_update);
    if ($updated1) { 
    	$datab->logsInsertRow($_SESSION['username'], 'UPDATE', 'stareaparate', 'bitiComanda,stareRetur,macPic,idApRetur', $array_update, 'WHERE idAparat='.intval($idAparat));
        echo 'Aparat updatat in baza cu success!';
    } else {
        echo 'Nu s-a putut updata aparatul in baza!';
    }  
    

/**
 * Pasul 6 - se sterge macul din MACPICNEASOCIAT
 */

$deleted = $datab->deleteRow('macpicneasociat',  'WHERE macPic=?', array($macDeAsociat));

// $macsMapper->deleteMac($mac);