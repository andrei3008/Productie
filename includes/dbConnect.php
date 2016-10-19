<?php

session_start();
$dbHost = "localhost";
$dbUser = "adi";
$dbPass = "adi77";

$con = new mysqli($dbHost, $dbUser, $dbPass);
if ($con->connect_errno) {
    echo "A aparut o eroare la conexiunea cu baza de date. " . $con->connect_error;
    exit();
}

function getResultSet($con, $query) {
    $rezultArray = [];
    $result = $con->query($query);
    while ($obj = $result->fetch_object()) {
        $rezultArray[] = $obj;
    }
    return $rezultArray;
}

function getUserAparate($idopertator, $con) {
    $userArray = [];
    $query = "SELECT "
            . "count(aparate.idLocatie) AS nr_aparate, "
            . "personal.idpers,"
            . " personal.nick "
            . "FROM brunersrl.aparate "
            . "INNER JOIN brunersrl.locatii "
            . "ON aparate.idLocatie = locatii.idlocatie "
            . "INNER JOIN brunersrl.personal "
            . "ON personal.idpers = locatii.idresp WHERE aparate.dtBlocare='1000-01-01' AND locatii.idOperator='" . $idopertator . "' GROUP BY personal.idpers ORDER BY personal.idpers ASC";
    $result = $con->query($query);
    while ($obj = $result->fetch_object()) {
        $userArray[$obj->nick] = $obj;
    }
    return $userArray;
}

function getLocatiiPersonal($operator, $con) {
    $userArray = [];
    $query = "SELECT"
            . " p.nick,"
            . " p.nume,"
            . " p.prenume,"
            . " p.telefon,"
            . " p.user,"
            . " p.pass,"
            . " p.idpers,"
            . " count(l.idresp) as nr_locatii "
            . "FROM"
            . " brunersrl.personal as p "
            . "INNER JOIN "
            . " brunersrl.locatii as l "
            . " ON p.idpers = l.idresp WHERE l.idOperator='" . $operator . "' GROUP BY p.idpers ASC";
    $result = $con->query($query);
    while ($obj = $result->fetch_object()) {
        $userArray[] = $obj;
    }
    return $userArray;
}

function getLocatii($operator, $con, $idResponsabil) {
    $locatii = [];
    $query = "SELECT "
            . " locatii.denumire,"
            . " locatii.idlocatie "
            . "FROM brunersrl.locatii "
            . "WHERE "
            . " locatii.idresp = " . $idResponsabil . " ";
    if ($operator != '') {
        $query.=" AND locatii.idOperator='" . $operator . "'";
    }
    $result = $con->query($query);
    while ($obj = $result->fetch_object()) {
        $locatii[] = $obj;
    }
    return $locatii;
}

function verifyUser($username, $password, $con) {
    $queryUser = "SELECT * FROM brunersrl.personal WHERE personal.user='" . $username . "'";
    $rezultat = $con->query($queryUser);
    $obj = $rezultat->fetch_object();
    if ($password == $obj->pass OR $password == NULL) {
        return $obj;
    }
    return FALSE;
}

function getOperatorLocatie($idLocatie, $con) {
    $query = "SELECT locatii.idOperator FROM brunersrl.locatii where locatii.idlocatie='" . $idLocatie . "'";
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj->operator;
}

/**
 * Insereaza in baza de date noii index pe ziua curenta
 * @param type $con
 * @param type $idAparat
 * @param type $idLocatie
 * @param type $idxIn
 * @param type $idxOut
 * @param type $an
 * @param type $luna
 */
function insertContori($con, $idAparat, $idLocatie, $idxIn, $idxOut) {
    $query = "INSERT INTO brunersrl.contormecanic" . date('Y') . date('n') . " (idAparat,idLocatie,idxInM,idxOutM,dtServer) VALUES ($idAparat,$idLocatie,$idxIn,$idxOut,'" . date('Y-m-d H:i:s') . "')";
    $con->query($query);
    echo $con->error;
}

/**
 * Functia verifica daca exista indexi pe ziua de azi
 * @param mysqli object $con
 * @param int $idAparat
 * @param int $idLocatie
 * @return int
 */
function verificaExistentaIndex($con, $idAparat, $idLocatie) {
    $query = "SELECT count(idAparat) as numarAparate FROM brunersrl.contormecanic" . date('Y') . date('n') . " "
            . "WHERE contormecanic" . date('Y') . date('n') . ".idAparat =" . $idAparat . " AND contormecanic" . date('Y') . date('n') . ".dtServer BETWEEN '" . date('Y') . "-" . date('m') . "-" . date('d') . " 00:00:00' AND '" . date('Y') . "-" . date('m') . "-" . date('d') . " 23:59:59'";
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj->numarAparate;
}

/**
 * Updateaza index pe ziua curenta in baza de date 
 * @param type $con
 * @param type $luna
 * @param type $an
 * @param type $zi
 * @param type $idxIn
 * @param type $idxOut
 * @param type $idAparat
 * @param type $idLocatie
 */
function updateContori($con, $idxIn, $idxOut, $idAparat, $idLocatie) {
    $query = "UPDATE "
            . "brunersrl.contormecanic" . date('Y') . date('n') . " "
            . "SET contormecanic" . date('Y') . date('n') . ".idxInM =" . $idxIn . ", contormecanic" . date('Y') . date('n') . ".idxOutM = " . $idxOut
            . " WHERE contormecanic" . date('Y') . date('n') . ".idAparat =" . $idAparat . " AND contormecanic" . date('Y') . date('n') . ".dtServer BETWEEN '" . date('Y') . "-" . date('m') . "-" . date('d') . " 00:00:00' AND '" . date('Y') . "-" . date('m') . "-" . date('d') . " 23:59:59'";
    $con->query($query);
}

function updateStareAparate($con, $idxIn, $idxOut, $idAparat) {
    $query = "UPDATE brunersrl.stareaparate SET lastIdxInM=$idxIn , lastIdxOutM=$idxOut WHERE stareaparate.idAparat=$idAparat";
    $con->query($query);

    if ($con->affected_rows) {
        echo $con->error;
        return FALSE;
    } else {
        return TRUE;
    }
}

function getAparateDepozitByResponsabil($idResponsabil, $operator, $con) {
    $query = "SELECT count(aparate.idAparat) AS aparateDepozit
            FROM brunersrl.aparate 
            INNER JOIN brunersrl.locatii ON aparate.idLocatie = locatii.idlocatie
            INNER JOIN brunersrl.personal ON personal.idpers = locatii.idresp
            WHERE aparate.dtBlocare!='1000-01-01' AND locatii.dtInchidere='1000-01-01' 
             ";
    if ($idResponsabil != null) {
        $query.="AND personal.idpers='" . $idResponsabil . "' ";
    }
    if ($operator != null) {
        $query .="AND locatii.idOperator='" . $operator . "' ";
    }
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj->aparateDepozit;
}

function checkPasswords($oldPassDb, $oldPass, $newPass1, $newPass2) {
    if ($oldPassDb != $oldPass)
        return 'Vechea Parola este incorecta!!!';
    if ($newPass1 != $newPass2)
        return 'Cele doua parole nu sunt identice!!!!';
    if (strlen($newPass1) < 4)
        return 'Parola are mai putin de 4 (patru) caractere!!!';
    return TRUE;
}

function printDialog($tip,$mesaj) {
    return "<div class='alert alert-$tip' id='dispare'>
                <strong>Atentie!</strong><br/>$mesaj.
           </div>";
}

function getUserInfoById($userid,$con){
    $query = "SELECT * FROM brunersrl.personal WHERE personal.idpers='$userid'";
    $result = $con->query($query);
    $obj = $result->fetch_object();
    return $obj;
}