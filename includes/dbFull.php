<?php
/**
 *
 */


/**
 * Class dbFull
 */
class dbFull extends mysqli
{

    /**
     * @var
     */
    public $con;
    /** @var string $database */
    protected $database = 'brunersrl';

    /**
     * dbFull constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param null $database
     */
    public function __construct($host, $user, $password, $database = null)
    {
         parent::__construct($host, $user, $password);
         $this->select_db($this->database);
    }

    /**
     *
     * @param type $idAparat
     * @return int
     */
    public function getNrEroriAparat($idAparat)
    {
        $query = "SELECT count(idAparat) as nrErori FROM $this->database.errorpk WHERE idAparat=$idAparat";
        $result = $this->query($query);
        if ($result) {
            $data = $result->fetch_object();
            return $data->nrErori;
        }
        return 0;
    }

    /**
     * @param $an
     * @param $luna
     * @param $zi
     * @param $idAparat
     * @return object|stdClass
     */
    public function getMaxIdBeforeDate($an, $luna, $zi, $idAparat)
    {
        $query = "SELECT 
                    aparate.idAparat,
                    contor.idxInM,
                    contor.idxOutM,
                    contor.dtServer,
                    aparate.seria
                FROM $this->database.contormecanic$an$luna as contor
                INNER JOIN $this->database.aparate ON aparate.idAparat = contor.idAparat
                WHERE dtServer <= '$an-$luna-$zi' AND aparate.idAparat=$idAparat order by dtServer desc LIMIT 1 ";
        $result = $this->query($query);
        if ($result) {
            $data = $result->fetch_object();
        }
        return $data;
    }

    /**
     * @param $secret
     * @param $idpers
     * @return bool
     */
    public function updateUserTel($secret, $idpers)
    {
        $query = "UPDATE $this->database.personal SET codTel='$secret' WHERE idpers=$idpers";
        $this->query($query);
        if ($this->affected_rows > 1) {
            return TRUE;
        }
        return false;
    }

    /**
     * @param $username
     * @return bool|object|stdClass
     */
    public function getUserByUserName($username)
    {
        $query = "SELECT * FROM $this->database.personal WHERE user ='$username'";
        $result = $this->query($query);
        if ($result) {
            $obj = $result->fetch_object();
        } else {
            $obj = FALSE;
        }
        return $obj;
    }

    /**
     * @param $secret
     * @param $idpers
     * @return bool
     */
    public function updateUserPc($secret, $idpers)
    {
        $query = "UPDATE $this->database.personal SET codpc='$secret' WHERE idpers=$idpers";
        echo $query;
        $this->query($query);
        if ($this->affected_rows > 1) {
            return TRUE;
        }
        return false;
    }

    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return string
     */
    public function getDataPornire($idAparat, $an, $luna, $zi)
    {
        $query = "SELECT "
            . "extract(hour FROM dtPornire) as ora,"
            . "extract(minute FROM dtPornire) as minute,"
            . "extract(second FROM dtPornire) as secunde "
            . "FROM $this->database.contormecanic$an$luna WHERE dtServer "
            . "BETWEEN '$an-$luna-$zi 00:00:00' AND '$an-$luna-$zi 23:59:59' AND idAparat=$idAparat";

        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            $a = $result->fetch_object();
            $data = $a->ora . ':' . ($a->minute < 10 ? '0' . $a->minute : $a->minute) . ':' . $a->secunde;
        } else {
            $data = '';
        }
        return $data;
    }

    /**
     * @param $conection
     */
    private function setCon($conection)
    {
        $this->con = $conection;
    }

    /**
     * @param $idAparat
     * @return string
     */
    public function tipEroare($idAparat)
    {
        $rezultat = '';
        $query = "SELECT exceptia FROM $this->database.errorpk WHERE idAparat=$idAparat ORDER BY idPachet DESC LIMIT 1";
        $result = $this->query($query);
        $data = $result->fetch_object();
        if (strpos($data->exceptia, 'IN') !== FALSE) {
            $rezultat .= ' IN ';
        }
        if (strpos($data->exceptia, 'OUT') !== FALSE) {
            $rezultat .= ' OUT';
        }
        if (strpos($data->exceptia, 'Seria') !== FALSE) {
            return 'Seria';
        }
        return $rezultat;
    }

    /**
     * @param $database
     */
    private function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @param $userName
     * @return object|stdClass
     */
    public function getUserInfo($userName)
    {
        $query = "SELECT * FROM $this->database.personal WHERE user='$userName' LIMIT 1";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $idUser
     * @param $password
     * @return bool
     */
    public function setNewUserPassword($idUser, $password)
    {
        $query = "UPDATE $this->database.personal SET pass='$password', resetPass='1' WHERE idpers='$idUser'";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param $idopertator
     * @param SessionClass $appSettings
     * @return array
     */
    public function getUserAparate($idopertator, SessionClass $appSettings)
    {
        $userArray = [];
        /** @var string $this ->database $ */
        $query = "SELECT "
            . "count(aparate.idLocatie) AS nr_aparate, "
            . "personal.idpers,"
            . " personal.nick "
            . "FROM $this->database.aparate "
            . "INNER JOIN $this->database.locatii "
            . "ON aparate.idLocatie = locatii.idlocatie "
            . "INNER JOIN $this->database.personal "
            . "ON personal.idpers = locatii.idresp WHERE
            (((aparate.dtActivare >= '{$appSettings->inceputLuna()}' AND aparate.dtActivare <= '{$appSettings->sfarsitLuna()}'))
             OR
             (aparate.dtBlocare >='{$appSettings->inceputLuna()}' AND aparate.dtBlocare <= '{$appSettings->sfarsitLuna()}') OR aparate.dtBlocare = '1000-01-01')
            AND locatii.idOperator='" . $idopertator . "' GROUP BY personal.idpers ORDER BY personal.idpers ASC";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $userArray[$obj->nick] = $obj;
        }
        return $userArray;
    }

    /**
     * @param $operator
     * @param SessionClass $appSettings
     * @return array
     */
    public function getLocatiiPersonal($operator, SessionClass $appSettings)
    {
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
            . " $this->database.personal as p "
            . "INNER JOIN "
            . " $this->database.locatii as l "
            . " ON p.idpers = l.idresp WHERE l.idOperator='" . $operator . "' AND (l.dtInfiintare <= '{$appSettings->sfarsitLuna()}' AND l.dtInchidere <= '{$appSettings->sfarsitLuna()}') GROUP BY p.idpers ASC";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $userArray[$obj->idpers] = $obj;
            }
        }
        return $userArray;
    }
    /**
     * @param $operator
     * @param SessionClass $appSettings
     * @return array
     */
    public function getLocatiiPersonal2($operator, SessionClass $appSettings)
    {
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
            . " $this->database.personal as p "
            . "INNER JOIN "
            . " $this->database.locatii as l "
            . " ON p.idpers = l.idresp WHERE l.idOperator='" . $operator . "' AND (l.dtInchidere = '1000-01-01' OR (l.dtInchidere <= '{$appSettings->sfarsitLuna()}' AND l.dtInchidere >= '{$appSettings->inceputLuna()}')) GROUP BY p.idpers ASC";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $userArray[$obj->idpers] = $obj;
            }
        }
        return $userArray;
    }
    /**
     * @param $idLocatie
     * @return string
     */
    public function getCuloareLocatie($idLocatie)
    {
        $now = new DateTime();
        $now->format("Y-m-d H:i:s");
        $query = "SELECT stareaparate.idaparat,ultimaConectare FROM $this->database.stareaparate
            INNER JOIN $this->database.aparate ON stareaparate.idAparat = aparate.idAparat
            WHERE idLocatie=$idLocatie";
        $result = $this->query($query);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $last = new DateTime($row->ultimaConectare);
                $last->format("Y-m-d H:i:s");
                $diferenta = $now->diff($last);
                $ore = $diferenta->format('%h');
                $zile = $diferenta->format('%a');
                if ($ore < 1 AND $zile < 1) {
                    return 'verde';
                }
            }
            return 'rosu';
        }
    }

    /**
        MUTAT databFull
        
     * @param $operator
     * @param $idResponsabil
     * @param $an
     * @param $luna
     * @param string $sort
     * @return array
     */
    public function getLocatii($operator, $idResponsabil, $an, $luna, $sort = 'ASC')
    {
        $locatii = [];
        $verzi = [];
        $rosii = [];
        $partial = [];
        $query = "SELECT "
            . " locatii.denumire, "
            . " locatii.idlocatie "
            . "FROM $this->database.locatii "
            . "WHERE "
            . " locatii.idresp = " . $idResponsabil . " AND (locatii.dtInchidere='1000-01-01' OR (locatii.dtInchidere >= '$an-$luna-01' AND locatii.dtInchidere <= '$an-$luna-31') )";
        if ($operator != '') {
            $query .= " AND locatii.idOperator='" . $operator . "' ";
        }
        /**************************************************
         *  ADDED - SILVIU - 02.09.2016
        **************************************************/
        $query .= " ORDER BY locatii.idOperator ASC";
        /*- END ADDED ------------------------------------*/
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $nrAparate = $this->getNrAparateByDate($obj->idlocatie, $an, $luna);
            if ($nrAparate == 0) {
                $locatii[$obj->idlocatie][$nrAparate] = $obj;
            } else {
                if ($sort == 'ASC' OR $sort == 'DESC' OR $sort = null) {
                    $culoareAparat = $this->getCuloareLocatie($obj->idlocatie);
                    if ($culoareAparat == 'rosu') {
                        $rosii[$obj->idlocatie][$nrAparate] = $obj;
                    } else {
                        $verzi[$obj->idlocatie][$nrAparate] = $obj;
                    }
                } else {
                    if ($this->nrEroriLocatie($obj->idlocatie) > 0) {
                        $rosii[$obj->idlocatie][$nrAparate] = $obj;
                    } else {
                        $verzi[$obj->idlocatie][$nrAparate] = $obj;
                    }
                }
            }
        }
        if ($sort == 'ASC') {
            $final = array_merge($verzi, $rosii);
        } else {
            $final = array_merge($rosii, $verzi);
        }
        $locatii = array_merge($final, $locatii);
        return $locatii;
    }

    /**
     * @param $idLocatie
     * @return mixed
     */
    public function nrEroriLocatie($idLocatie)
    {
        $query = "SELECT count(idAparat) as nrErori  FROM $this->database.errorpk e WHERE e.idLocatie=$idLocatie";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->nrErori;
    }

    /**
     * @param $username
     * @param $password
     * @return bool|object|stdClass
     */
    public function verifyUser($username, $password)
    {
        if (!$username == '') {
            $queryUser = "SELECT * FROM $this->database.personal WHERE personal.user='" . $username . "'";
            $rezultat = $this->query($queryUser);
            echo $this->error;
            $obj = $rezultat->fetch_object();

            if ($this->affected_rows > 0 AND $password == $obj->pass OR $password == NULL) {
                return $obj;
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }

    /**
     * ADDED - SILVIU - 02.09.2016
     */
    public function getOperatorLocatie($idLocatie)
    {
        $query = "SELECT locatii.idOperator FROM $this->database.locatii where locatii.idlocatie='" . $idLocatie . "'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->operator;
    }
    /**
     * @param $idLocatie
     * @return mixed
     */
    public function getNumeOperatorLocatie($idLocatie)
    {
        $query = "SELECT locatii.idOperator, operatori.denFirma FROM $this->database.locatii, $this->database.operatori where locatii.idlocatie='" . $idLocatie . "' AND locatii.idOperator = operatori.idoperator";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->denFirma;
    }
    /**
     * @param int $idAparat
     * @param int $idLocatie
     * @param int $idxIn
     * @param int $idxOut
     */
    public function insertContori($idAparat, $idLocatie, $idxIn, $idxOut, $an, $luna, $zi)
    {
        $data = date('Y-m-d');
        $query = "INSERT INTO $this->database.contormecanic" . $an . $luna . " (idAparat,idLocatie,idxInM,idxOutM,idxBetM,dtServer,dtInserare,setatDe,dtPic) VALUES ($idAparat,$idLocatie,$idxIn,$idxOut,'0','" . $an . '-' . $luna . '-' . $zi . ' ' . date('H:i:s') . "','$data','" . $_SESSION['username'] . "','$an-".($luna-1)."-$zi')";
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Functia verifica daca exista indexi pe ziua de azi
     * @param mysqli object $con
     * @param int $idAparat
     * @param int $idLocatie
     * @return int nr_aparate
     */
    public function verificaExistentaIndex($idAparat, $an, $luna, $zi)
    {
        $query = "SELECT count(idAparat) as numarAparate FROM $this->database.contormecanic" . $an . $luna . " "
            . "WHERE contormecanic" . $an . $luna . ".idAparat =" . $idAparat . " AND contormecanic" . $an . $luna . ".dtServer BETWEEN '" . $an . "-" . $luna . "-" . $zi . " 00:00:00' AND '" . $an . "-" . $luna . "-" . $zi . " 23:59:59'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->numarAparate;
    }

    /**
     * @param $idxIn
     * @param $idxOut
     * @param $idAparat
     * @param $an
     * @param $zi
     * @param $luna
     * @return bool
     */
    public function updateContori($idxIn, $idxOut, $idAparat, $an, $zi, $luna)
    {
        $data = date('Y-m-d');
        $query = "UPDATE "
            . "$this->database.contormecanic" . $an . $luna . " "
            . "SET contormecanic" . $an . $luna . ".idxInM =" . $idxIn . ", contormecanic" . $an . $luna . ".idxOutM = " . $idxOut
            . ", contormecanic" . $an . $luna . ".dtInserare='$data', contormecanic$an$luna.setatDe='" . $_SESSION['username'] . "', dtPic='$an-".($luna-1)."-$zi' WHERE contormecanic" . $an . $luna . ".idAparat =" . $idAparat . " AND contormecanic" . $an . $luna . ".dtServer BETWEEN '" . $an . "-" . $luna . "-" . $zi . " 00:00:00' AND '" . $an . "-" . $luna . "-" . $zi . " 23:59:59'";
        $this->query($query);
        if ($this->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $info
     * @return bool|string
     */
    public function insertUser($info)
    {
        if (!$this->isUserNameUnique($info['user'])) {
            return 'Numele de utilizator nu este unic';
        }
        $data = date('Y-m-d');
        $query = "INSERT INTO $this->database.personal (nume,prenume,nick,user,pass,grad,data) "
            . " VALUES ('" . $info['nume'] . "','','" . $info['nick'] . "','" . $info['user'] . "','" . $info['parola'] . "','0','$data')";
        $result = $this->query($query);
        echo $this->error;
        if ($result)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $username
     * @return bool
     */
    public function isUserNameUnique($username)
    {
        $query = "SELECT idpers FROM $this->database.personal WHERE user='$username'";
        $result = $this->query($query);
        if (!$result)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $id
     * @return object|stdClass
     */
    public function getUserById($id)
    {
        $query = "SELECT * FROM $this->database.personal WHERE idpers='$id'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $info
     * @return bool|string
     */
    public function updateUser($info)
    {
        $existingInfo = $this->getUserById($info['idUtilizator']);
        if ($existingInfo->user != $info['user']) {
            if (!$this->isUserNameUnique($info['user'])) {
                return 'Username trebuie sa fie unic!';
            }
        }
        $query = "UPDATE $this->database.personal SET nume='" . $info['nume'] . "',"
            . " nick='" . $info['nick'] . "', pass='" . $info['parola'] . "', user='" . $info['user'] . "' "
            . "WHERE idpers='" . $info['idUtilizator'] . "' ";
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteUser($id)
    {
        $query = "DELETE FROM $this->database.personal WHERE idpers=$id";
        $this->query($query);
        if ($this->affected_rows > 0)
            return true;
        return false;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $personal = [];
        $query = "SELECT * FROM $this->database.personal ORDER BY personal.idpers DESC";
        $result = $this->query($query);
        if ($result) {
            while ($user = $result->fetch_object()) {
                $personal[] = $user;
            }
        }
        return $personal;
    }

    /**
     * @param $idxIn
     * @param $idxOut
     * @param $idAparat
     * @return bool
     */
    public function updateStareAparate($idxIn, $idxOut, $idAparat)
    {
        $azi = date('Y-m-d H:i:s');
        $query = "UPDATE $this->database.stareaparate SET lastIdxInM=$idxIn , lastIdxOutM=$idxOut, dtLastM='$azi' WHERE stareaparate.idAparat=$idAparat";
        $this->query($query);

        if ($this->affected_rows) {
            echo $this->error;
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * @param $idResponsabil
     * @param $operator
     * @return mixed
     */
    public function getAparateDepozitByResponsabil($idResponsabil, $operator)
    {
        $query = "SELECT count(aparate.idAparat) AS aparateDepozit
            FROM $this->database.aparate
            INNER JOIN $this->database.locatii ON aparate.idLocatie = locatii.idlocatie
            INNER JOIN $this->database.personal ON personal.idpers = locatii.idresp
            WHERE locatii.denumire LIKE \"%$operator%\"
             ";
        if ($idResponsabil != null) {
            $query .= "AND personal.idpers='" . $idResponsabil . "' ";
        }
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->aparateDepozit;
    }

    /**
     * @param $userid
     * @param $con
     * @return object|stdClass
     */
    public function getUserInfoById($userid, $con)
    {
        $query = "SELECT * FROM this->database.personal WHERE personal.idpers='$userid'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $idLocatie
     * @return object|stdClass
     */
    public function getLocationInfo($idLocatie)
    {
        $queryInfoLocatie = "SELECT
            locatii.denumire as nickLocatie,
            locatii.idlocatie,
            locatii.idOperator,
            locatii.fond,
            locatii.telefon,
            locatii.persContact,
            locatii.adresa,
            locatii.regiune,
            locatii.localitate,
            locatii.contractInternet,
            firme.manager,
            firme.denumire
            FROM $this->database.locatii
            INNER JOIN $this->database.firme
            ON locatii.idfirma=firme.idfirma WHERE locatii.idlocatie=$idLocatie";
        $resultInfoLocatie = $this->query($queryInfoLocatie);
        $obj = $resultInfoLocatie->fetch_object();
        return $obj;
    }

    /**
     * @param $idLocatie
     * @param $idResponsabil
     * @return array
     */
    public function getInfoAparate($idLocatie, $idResponsabil, $luna, $an)
    {
        $aparate = array();
        $queryInfoAparate = "SELECT
	    c1.idAparat,
        c1.seria,
        c1.tip,
        c1.pozitieLocatie,
        c1.dtBlocare,
        c1.dtActivare,
        stareaparate.lastidxInM,
        stareaparate.lastidxOutM,
		stareaparate.lastIdxInE,
        stareaparate.lastIdxOutE,
        stareaparate.dtLastM as dataMax,
        stareaparate.ultimaConectare,
        stareaparate.verSoft,
        stareaparate.dtLastM,
        stareaparate.dtLastE,
        stareaparate.ipPic,
        stareaparate.ipPic3g,
        stareaparate.macPic
        FROM $this->database.aparate as c1
        INNER JOIN $this->database.stareaparate ON stareaparate.idAparat = c1.idAparat
        INNER JOIN $this->database.locatii ON locatii.idlocatie = c1.idLocatie 
        WHERE " . (($idLocatie == 0) ? "" : "c1.idLocatie=$idLocatie  AND") . " locatii.idresp='$idResponsabil' AND
        (c1.dtBlocare='1000-01-01' OR dtBlocare >= '$an-$luna-00') AND c1.dtActivare <= '$an-$luna-32' ";
        $queryInfoAparate .= " GROUP BY c1.idAparat  ORDER BY c1.pozitieLocatie ASC";
        $result = $this->query($queryInfoAparate);
        while ($obj = $result->fetch_object()) {
            $aparate[] = $obj;
        }
        return $aparate;
    }
    /**
     * ADDED - 10.09.2016 - SILVIU
     * @param $idLocatie
     * @param $idResponsabil
     * @return array
     */
    public function getInfoAparateElectr($idLocatie, $idResponsabil, $luna, $an)
    {
        $aparate = array();
        $queryInfoAparate = "SELECT
        c1.idAparat,
        c1.seria,
        c1.tip,
        c1.pozitieLocatie,
        c1.dtBlocare,
        c1.dtActivare,
        stareaparate.lastIdxInE,
        stareaparate.lastIdxOutE,
        stareaparate.dtLastE as dataMax,
        stareaparate.ultimaConectare,
        stareaparate.verSoft,
        stareaparate.ipPic,
        stareaparate.ipPic3g,
        stareaparate.macPic
        FROM $this->database.aparate as c1
        INNER JOIN $this->database.stareaparate ON stareaparate.idAparat = c1.idAparat
        INNER JOIN $this->database.locatii ON locatii.idlocatie = c1.idLocatie 
        WHERE " . (($idLocatie == 0) ? "" : "c1.idLocatie=$idLocatie  AND") . " locatii.idresp='$idResponsabil' AND
        (c1.dtBlocare='1000-01-01' OR dtBlocare >= '$an-$luna-00') AND c1.dtActivare <= '$an-$luna-32' ";
        $queryInfoAparate .= " GROUP BY c1.idAparat  ORDER BY c1.pozitieLocatie ASC";

        $result = $this->query($queryInfoAparate);
        while ($obj = $result->fetch_object()) {
            $aparate[] = $obj;
        }
        return $aparate;
    }
    /**
     * @param $an
     * @param $luna
     * @param $zi
     * @param $idAparat
     * @return object|stdClass
     */
    public function getVariabile($an, $luna, $zi, $idAparat)
    {
        $query = "SELECT variabile.fm_mec, pi_mec, MAX(variabile.data) FROM $this->database.variabile WHERE variabile.data <= '" . $an . "-" . ($luna + 1) . "-" . $zi . "' AND variabile.idAparat='$idAparat'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return array
     */
    public function getCashInCashOut($idAparat, $an, $luna, $zi)
    {
        $rezultat = [];
        $query = "SELECT cashIn, cashOut FROM $this->database.contormecanic$an$luna WHERE dtServer> '$an-$luna-$zi 00:00:00' AND dtServer < '$an-$luna-$zi 23:59:59' AND idAparat=$idAparat";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            $data = $result->fetch_object();
            $rezultat['cashIn'] = $data->cashIn;
            $rezultat['cashOut'] = $data->cashOut;
        } else {
            $rezultat['cashIn'] = 0;
            $rezultat['cashOut'] = 0;
        }
        return $rezultat;
    }

	/**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return array
     */
    public function getCashInCashOutElectronic($idAparat, $an, $luna, $zi)
    {
        $rezultat = [];
        $query = "SELECT cashIn, cashOut FROM $this->database.contorelectronic$an$luna WHERE dtServer> '$an-$luna-$zi 00:00:00' AND dtServer < '$an-$luna-$zi 23:59:59' AND idAparat=$idAparat";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            $data = $result->fetch_object();
            $rezultat['cashIn'] = $data->cashIn;
            $rezultat['cashOut'] = $data->cashOut;
        } else {
            $rezultat['cashIn'] = 0;
            $rezultat['cashOut'] = 0;
        }
        return $rezultat;
    }
	
    /**
     * @return array
     */
    public function getAllResponsabiliAparate()
    {
        $randuri = [];
        $query = "SELECT p.nick, a.seria, l.denumire, st.lastIdxInM, st.lastIdxOutM FROM $this->database.aparate a
              INNER JOIN $this->database.locatii l ON a.idLocatie = l.idlocatie
              INNER JOIN $this->database.personal p ON p.idpers = l.idresp
              INNER JOIN $this->database.stareaparate st ON st.idAparat = a.idAparat";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $randuri[] = $obj;
        }
        return $randuri;
    }

    /**
     * @param $idLocatie
     * @return object|stdClass
     */
    public function getInfoFirma($idLocatie)
    {
        $query = "SELECT "
            . "locatii.idfirma, "
            . "locatii.adresa, "
            . "locatii.regiune, "
            . "firme.denumire,"
            . "personal.nume,"
            . "personal.prenume,"
            . "personal.privilegii "
            . "FROM $this->database.locatii "
            . "INNER JOIN "
            . "$this->database.firme O"
            . "N locatii.idfirma=firme.idfirma "
            . "INNER JOIN $this->database.personal ON locatii.idresp = personal.idpers"
            . " WHERE locatii.idlocatie='$idLocatie';";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $an
     * @param $luna
     * @param $zi
     * @param $idLocatie
     * @return array
     */
    public function getIndexByDate($an, $luna, $zi, $idLocatie)
    {
        $queryAparate = "SELECT "
            . "aparate.idAparat,"
            . "aparate.seria, "
            . "contormecanic" . $an . $luna . ".idxInM,"
            . "contormecanic" . $an . $luna . ".idxOutM,"
            . "contormecanic" . $an . $luna . ".dtServer"
            . " FROM "
            . "$this->database.aparate "
            . "INNER JOIN "
            . "$this->database.contormecanic" . $an . $luna
            . " ON "
            . "aparate.idAparat = contormecanic" . $an . $luna . ".idAparat "
            . "WHERE"
            . " (contormecanic" . $an . $luna . ".dtServer BETWEEN '" . $an . "-" . $luna . "-" . $zi . " 00:00:00' AND '" . $an . "-" . $luna . "-" . $zi . " 23:59:59') "
            . "AND aparate.idlocatie='" . $idLocatie . "';";
        $result = $this->query($queryAparate);
        $randuri = [];
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $randuri[] = $obj;
            }
        }
        return $randuri;
    }

    /**
     * @param $an
     * @param $luna
     * @param $idLocatie
     * @return array
     */
    public function getMaxIndexLunar($an, $luna, $idLocatie)
    {
        $max = [];
        $queryMax = "SELECT
                        aparate.idAparat,
                        aparate.seria,
                        contor.idxInM,
                        contor.idxOutM,
                        contor.dtServer,
                        contor.setatDe
                FROM {$this->database}.contormecanic{$an}{$luna} as contor
                INNER JOIN {$this->database}.aparate ON contor.idAparat = aparate.idAparat
                WHERE contor.idxInM in
                (SELECT max(idxInM) FROM {$this->database}.contormecanic{$an}{$luna} contor2
                WHERE contor2.idLocatie='{$idLocatie}' GROUP BY contor2.idAparat ORDER by contor2.idAparat ASC) AND contor.idlocatie=$idLocatie GROUP by aparate.idAparat";
        $result = $this->query($queryMax);
        if ($this->affected_rows > 0) {
            while ($obj = $result->fetch_object()) {
                $max[] = $obj;
            }
        }
        return $max;
    }

    /**
     * @param $idResp
     * @param $an
     * @param $luna
     * @return array
     */
    public function getEroriByDays($idResp, $an, $luna)
    {
        $erori = [];
        $query = "SELECT
            idpachet,
            serieAparat,
            idAparat,
            errorpk.idlocatie,
            extract(day FROM dataServer) as zi
        FROM $this->database.errorpk
        INNER JOIN $this->database.locatii ON locatii.idLocatie = errorpk.idLocatie
        INNER JOIN $this->database.personal ON personal.idpers = locatii.idresp
        WHERE dataServer > '$an-$luna-00' AND dataServer < '$an-$luna-32' and locatii.idresp= $idResp";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            while ($row = $result->fetch_object()) {
                $erori[$row->idlocatie][$row->idAparat][$row->zi] = $row;
            }
        }
        return $erori;
    }

    /**
     * @param $an
     * @param $luna
     * @param $idLocatie
     * @return array
     */
    public function getMinIndexLunar($an, $luna, $idLocatie)
    {
        $min = [];
        $queryMin = "SELECT"
            . " aparate.idAparat,"
            . " aparate.seria,"
            . " min(contor.idxInM) as idxInM,"
            . " min(contor.idxOutM) as idxOutM,"
            . " contor.dtServer"
            . " FROM $this->database.contormecanic" . $an . $luna . " as contor"
            . " INNER JOIN $this->database.aparate "
            . " ON contor.idAparat = aparate.idAparat"
            . " WHERE  "
            . "  contor.idLocatie=" . $idLocatie . ""
            . "  GROUP BY aparate.idAparat ORDER by contor.idAparat ASC";
        $result = $this->query($queryMin);
        if ($this->affected_rows > 0) {
            while ($obj = $result->fetch_object()) {
                $min[$obj->idAparat] = $obj;
            }
        }
        return $min;
    }

    /**
     * @param $idAparat
     * @param $luna
     * @param $an
     * @param $idLocatie
     * @return string
     */
    public function genereazaCastiguri($idAparat, $luna, $an, $idLocatie)
    {
        $indexi1 = $index2 = $this->getIndexiCMAparat($idAparat, $an, $luna);
        $variabile = $this->getVariabile($an, $luna, '31', $idAparat);
        $result = '';
        for ($i = 1; $i < count($indexi1); $i++) {
            $cashIn = ($indexi1[$i]->idxInM - $indexi1[$i - 1]->idxInM) * $variabile->fm_mec * $variabile->pi_mec;
            $cashOut = ($indexi1[$i]->idxOutM - $indexi1[$i - 1]->idxOutM) * $variabile->fm_mec * $variabile->pi_mec;
            $castig = $cashIn - $cashOut;
            $query = "UPDATE $this->database.contormecanic$an$luna cm SET cm.cashIn=$cashIn, cm.cashOut=$cashOut, cm.castig=$castig WHERE cm.idAparat=$idAparat and cm.dtServer='{$indexi1[$i]->dtServer}'";
            $this->query($query);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getResponsabiliInfo()
    {
        $users = [];
        $usersQuery = "SELECT
            personal.nick,
            personal.idpers,
            count(locatii.idlocatie)
            FROM $this->database.personal
            INNER JOIN $this->database.locatii
            ON locatii.idresp = personal.idpers GROUP BY personal.idpers";
        $result = $this->query($usersQuery);
        while ($obj = $result->fetch_object()) {
            $users[] = $obj;
        }
        return $users;
    }

    /**
     * @param $operator
     * @param null $idResponsabil
     * @return object|stdClass
     */
    public function getNrLocatiiResponsabili($operator, $idResponsabil = null)
    {
        $nrLocatii = [];
        $queryNrLocatii = "SELECT "
            . "count(locatii.idlocatie) AS nrLocatii "
            . "FROM $this->database.locatii " . ((isset($idResponsabil) ? " WHERE locatii.idresp=" . $idResponsabil . " AND " : ' WHERE ') . 'locatii.idOperator="' . $operator . '"');
        $resultNrLocatii = $this->query($queryNrLocatii);
        $obj = $resultNrLocatii->fetch_object();
        return $obj;
    }

    /**
     * @param $idResponsabil
     * @param $operator
     * @return object|stdClass
     */
    public function getNumarAparateResponsabil($idResponsabil, $operator)
    {
        if (!isset($idResponsabil)) {
            $queryNrAparate = "SELECT count(aparate.idAparat) AS nrAparate FROM $this->database.aparate INNER JOIN $this->database.locatii on locatii.idlocatie = aparate.idLocatie WHERE locatii.idOperator='" . $_SESSION['operator'] . "'";
        } else {
            $queryNrAparate = "SELECT count(aparate.idAparat) AS nrAparate"
                . " FROM $this->database.aparate "
                . " INNER JOIN $this->database.locatii "
                . " ON locatii.idlocatie = aparate.idLocatie "
                . " WHERE locatii.idresp = " . $idResponsabil . " AND locatii.idOperator='$operator'";
        }
        $obj = $this->query($queryNrAparate)->fetch_object();
        return $obj;
    }

    /**
     * @param $idResponsabil
     * @param $operator
     * @return array
     */
    public function getAllAparateResponsabil($idResponsabil, $operator)
    {
        $aparate = [];
        $queryAparateResponsabil = "SELECT
                aparate.seria,
                aparate.idLocatie,
                aparate.tip,
                avertizari.dtExpAutorizatie,
                avertizari.dtExpMetrologie
                FROM $this->database.aparate
                INNER JOIN $this->database.avertizari
                ON avertizari.idAparat = aparate.idAparat
                INNER JOIN $this->database.locatii
                ON aparate.idLocatie = locatii.idlocatie
                WHERE " . (isset($idResponsabil) ? " locatii.idresp=$idResponsabil AND locatii.idOperator='" . $operator . "' AND " : '')." dtBlocare = '1000-01-01'";
        $resultAparateResponsabil = $this->query($queryAparateResponsabil);
        while ($obj = $resultAparateResponsabil->fetch_object()) {
            $aparate[] = $obj;
        }

        return $aparate;
    }

    /**
     * @param $idResponsabil
     * @param $operator
     * @return array
     */
    public function allLocatiiResponsabil($idResponsabil, $operator)
    {
        $locatii = [];
        $queryLocatiiResponsabil = "select
            locatii.denumire,
            locatii.idlocatie,
            locatii.adresa,
            firme.denumire as denumireFirma
            FROM $this->database.locatii
            INNER JOIN $this->database.firme
            ON locatii.idfirma = firme.idfirma" . ((isset($idResponsabil) ? "  WHERE locatii.idresp = $idResponsabil AND" : ' WHERE ') . ' locatii.idOperator="' . $operator . '"');
        $result = $this->query($queryLocatiiResponsabil);
        while ($obj = $result->fetch_object()) {
            $locatii[] = $obj;
        }
        return $locatii;
    }

    /**
     * @param $operator
     * @return array
     */
    public function getDistinctRegionsName()
    {
        $queryRegiune = "SELECT DISTINCT locatii.regiune FROM $this->database.locatii";
        $result = $this->query($queryRegiune);
        while ($obj = $result->fetch_object()) {
            $regiuni[] = $obj;
        }
        return $regiuni;
    }

    /**
     * @param $operator
     * @return array
     */
    public function getPersonalNick()
    {
        $query = "SELECT personal.nick FROM $this->database.personal";
        $result = $this->query($query);
        while ($object = $result->fetch_object()) {
            $nicks[] = $object;
        }

        return $nicks;
    }

    /**
     * @param $operator
     * @return array
     */
    public function getLocationNames($idResp = null, $idOp = null)
    {
        $query = "SELECT DISTINCT locatii.denumire FROM $this->database.locatii ";
        if ($idResp != null or $idOp != null) {
            $query .= ' WHERE ';
        }
        if ($idResp != null) {
            $query .= " idresp=$idResp ";
            if ($idOp != null) {
                $query .= " AND ";
            }
        }
        if ($idOp != null) {
            $query .= " idOperator=$idOp ";
        }
        $query .= "ORDER BY locatii.denumire ASC";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $locatii[] = $obj;
        }

        return $locatii;
    }

    /**
     * @param $judet
     * @param $responsabil
     * @param $locatie
     * @param $serie
     * @param $detinator
     * @param $adresa
     * @param $metrologie
     * @param $contractInternet
     * @return array
     */
    public function getSearch($judet, $responsabil, $locatie, $serie, $detinator, $adresa, $metrologie, $contractInternet)
    {
        $rezultatCautare = [];
        $queryCautare = "SELECT "
            . " locatii.regiune,"
            . " personal.nick,"
            . " locatii.denumire as numeLocatie,"
            . " aparate.seria,"
            . " firme.denumire as numeFirma,"
            . " locatii.adresa,"
            . " aparate.tip,"
            . " locatii.contractInternet "
            . ' FROM ' . $this->database . '.locatii, ' . $this->database . '.aparate, ' . $this->database . '.personal, ' . $this->database . '.firme '
            . ' WHERE '
            . ' locatii.idlocatie = aparate.idLocatie AND '
            . ' locatii.idresp = personal.idpers AND '
            . " locatii.idfirma = firme.idfirma AND "
            . " locatii.regiune LIKE '%" . $judet . "%' AND "
            . " personal.nick LIKE '%" . $responsabil . "%' AND "
            . " locatii.denumire LIKE '%" . $locatie . "%' AND "
            . " aparate.seria LIKE '%" . $serie . "%' AND "
            . " firme.denumire LIKE '%" . $detinator . "%' AND "
            . " locatii.adresa LIKE '%" . $adresa . "%' AND "
            . " aparate.tip LIKE '%" . $metrologie . "%' AND "
            . " (contractInternet LIKE '%" . $contractInternet . "%' OR contractInternet IS NULL) AND aparate.dtBlocare='1000-01-01' "
            . " ORDER BY locatii.regiune ASC, personal.nick ASC, numeLocatie ASC";
        $result = $this->query($queryCautare);
        while ($obj = $result->fetch_object()) {
            $rezultatCautare[] = $obj;
        }
        return $rezultatCautare;
    }

    /**
     * @return array
     */
    public function getAparateDepozitByResponsabili()
    {
        $aparate = [];
        $query = "SELECT
                    p.nick,
                    ap.seria,
                    ap.idAparat,
                    stareaparate.lastIdxInM,
                    stareaparate.lastIdxOutM,
                    stareaparate.dtLastM,
                    ap.dtActivare,
                    l1.denumire as denumireLocActual,
                    l1.idOperator as operator,
                    l2.denumire as denumireLocVechi,
                    l2.idlocatie

                 FROM ((($this->database.aparate ap INNER JOIN $this->database.locatii l1 ON ap.idLocatie = l1.idlocatie)
                INNER JOIN $this->database.stareaparate ON stareaparate.idAparat =  ap.idAparat)
                inner join $this->database.locatii l2 on ap.idLocVechi = l2.idlocatie)
                INNER JOIN $this->database.personal p ON p.idPers = l2.idresp where l1.denumire LIKE \"%depozit%\" AND ap.dtBlocare='1000-01-01' ORDER by p.nick, l2.denumire";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $aparate[] = $obj;
        }
        return $aparate;
    }

    /**
     * @param $operator
     * @param $locatie
     * @return array
     */
    public function getLastIndexByLocation($operator, $locatie)
    {
        $serii = [];
        $query = "SELECT aparate.idAparat,aparate.seria, stareaparate.lastidxInM, stareAparate.lastidxOutM
                FROM $this->database.aparate INNER JOIN $this->database.stareaparate ON stareaparate.idAparat = aparate.idAparat
                INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie WHERE
                locatii.idOperator = '$operator' and locatii.idlocatie='$locatie'";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $serii[$obj->idAparat] = $obj;
        }
        return $serii;
    }

    /**
     * @param $oldPassDb
     * @param $oldPass
     * @param $newPass1
     * @param $newPass2
     * @return bool|string
     */
    public function checkPasswords($oldPassDb, $oldPass, $newPass1, $newPass2)
    {
        if ($oldPassDb != $oldPass)
            return 'Vechea Parola este incorecta!!!';
        if ($newPass1 != $newPass2)
            return 'Cele doua parole nu sunt identice!!!!';
        if (strlen($newPass1) < 4)
            return 'Parola are mai putin de 4 (patru) caractere!!!';
        return TRUE;
    }

    /**
     * @param $idResponsabil
     * @return object|stdClass
     */
    public function getResponsabilPersonalInfo($idResponsabil)
    {
        $query = "SELECT * FROM $this->database.personal WHERE personal.idpers=$idResponsabil";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj;
    }

    /**
     * @param $userulCareModifica
     * @param array $info
     * @return bool|string
     */
    public function updateResponsabil($userulCareModifica, $info = array())
    {
        $info = $this->sanitizePost($info);
        if (strlen($info['nume']) < 4) {
            return "Numele de utilizator trebuie sa fie compus din minim 4 caractere!";
        }
        if ($info['pass'] != $info['pass2']) {
            return "Cele doua parole nu coincid!";
        }
        $query = "UPDATE $this->database.personal ";
        $query .= " SET nume='" . $info['nume'] . "', prenume='" . $info['prenume'] . "', cnp='" . $info['cnp'] . "', adresa='" . $info['adresa'] . "',
        telefon='" . $info['telefon'] . "', email='" . $info['email'] . "' ";
        if (!empty($info['pass'])) {
            $query .= " , pass='" . $info['pass'] . "'";
        }
        if ($this->existsNick($info['nick'])) {
            $query .= " ,nick='" . $info['nick'] . "'";
        }
        if ($this->existsUsername($info['user'])) {
            $query .= ", user='" . $info['user'] . "'";
        }
        $query .= " WHERE personal.idpers='" . $info['id'] . "'";
        $this->logAction('UPDATE-RESPONSABIL', $query, $userulCareModifica);
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return "Nu s-a aplicat nici o modificare!";
    }

    /**
     * @param $nick
     * @return bool
     */
    public function existsNick($nick)
    {
        $query = "'SELECT * FROM $this->database.personal WHERE nick LIKE '%$nick%'";
        $this->query($query);
        if ($this->affected_rows > 0)
            return FALSE;
        return TRUE;
    }

    /**
     * @param $username
     * @return bool
     */
    public function existsUsername($username)
    {
        $query = "SELECT * FROM {$this->database}.personal WHERE user LIKE '%$username%'";
        $this->query($query);
        if ($this->affected_rows > 0)
            return FALSE;
        return TRUE;
    }

    /**
     * @param $action
     * @param $sql
     * @param $user
     * @return bool
     */
    public function logAction($action, $sql, $user)
    {
        $sql = str_replace("'", "", $sql);
        $sql = str_replace('"', "", $sql);
        $query = "INSERT INTO $this->database.logs (user,eveniment,statement,data) VALUES ('$user','$action','$sql','" . date('Y-m-d H:i:s') . "')";
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @return array
     */
    public function getSqlLogs()
    {
        $logs = [];
        $query = "SELECT logs.user, logs.eveniment, logs.statement, logs.data FROM $this->database.logs";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $logs[] = $obj;
        }
        return $logs;
    }

    /**
     * @param $post
     * @return array
     */
    public function sanitizePost($post)
    {
        $sanitized = [];
        if (isset($post)) {
            foreach ($post as $key => $value) {
                $sanitized[$key] = $this->real_escape_string($value);
            }
        }
        return $sanitized;
    }

    /**
     * @param $idLocatie
     * @param $nume
     * @param $cantitate
     * @param $stare
     * @param $observatii
     * @param $user
     * @return bool
     */
    public function insertInventar($idLocatie, $nume, $cantitate, $stare, $observatii, $user)
    {
        $data = date('Y-m-d');
        $query = "INSERT INTO $this->database.inventar (idlocatie,denumire,cantitate,stare,observatii,dtActivare,dtBlocare) VALUES
                  ('$idLocatie','$nume','$cantitate','$stare','$observatii',$data,'1000-01-01')";
        $this->query($query);
        $this->logAction('INSERARE-INVENTAR', $query, $user);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function getInventar($idLocatie)
    {
        $inventar = [];
        $query = "SELECT ie.denumire, i.cantitate, i.stare, i.observatii FROM $this->database.inventar i INNER JOIN $this->database.elementeinventar ie ON ie.idelement = i.idelement WHERE i.idlocatie=$idLocatie";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $inventar[] = $obj;
            }
        }
        return $inventar;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function getAngajati($idLocatie)
    {
        $angajati = [];
        $query = "SELECT p.nume, p.telefon, p.prenume, p.email, p.idpers FROM $this->database.personal p WHERE p.idlocatie=$idLocatie";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            if (strpos($obj->telefon, 'p') !== FALSE) {
                $angajati[] = $obj;
            }
        }
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            if (strpos($obj->telefon, 'p') == FALSE) {
                $angajati[] = $obj;
            }
        }
        return $angajati;
    }

    /**
     * @param $idVechi
     * @param $idNou
     * @param $telefon
     * @return bool
     */
    public function updateNumarPrincipalAngajati($idVechi, $idNou, $telefon)
    {
        $queryRemoveP = "UPDATE $this->database.personal SET telefon = replace(telefon,'p' ,'') WHERE idpers='$idVechi'";
        $this->query($queryRemoveP);
        echo $this->error;
        $this->logAction('UPDATE-PERSONAL', $queryRemoveP, $_SESSION['username']);
        $telefon = $telefon . 'p';
        $queryNewP = "UPDATE $this->database.personal SET telefon='$telefon' WHERE personal.idpers='$idNou'";

        $this->query($queryNewP);

        echo $this->error;
        if ($this->affected_rows > 0) {
            $this->logAction('UPDATE-PERSONAL', $queryNewP, $_SESSION['username']);
            return TRUE;
        } else {
            return FALSE;
        }
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @return int
     */
    public function selectNumarAngajati($idLocatie)
    {
        $query = "SELECT user as nrAngajati FROM $this->database.personal WHERE idlocatie='$idLocatie' ORDER BY personal.user DESC LIMIT 1 ";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        $parti = explode('_', $obj->nrAngajati);
        return (isset($parti[1]) ? $parti[1] : 0);
    }

    /**
     * @param $info
     * @return bool
     */
    public function insertAnagajatBar($info)
    {
        $numarAngajat = $this->selectNumarAngajati($info['idLocatie']) + 1;
        if (!isset($info['nume']) OR $info['nume'] == '') {
            $info['nume'] = 'Angajat ' . $numarAngajat;
        }
        if (!isset($info['nick']) OR $info['nick'] == '') {
            $info['nick'] = 'Angajat ' . $numarAngajat;
        }
        $username = 'user' . $info['idLocatie'] . '_' . $numarAngajat;
        $data = date('Y-m-d');
        $query = "INSERT INTO $this->database.personal (idlocatie,nume,prenume,nick,telefon,email,grad,user,pass,data) VALUES (
                  '" . $info['idLocatie'] . "','" . $info['nume'] . "','" . $info['prenume'] . "','" . $info['nick'] . "','" . $info['telefon'] . "','" . $info['email'] . "','5','$username','" . rand(1000, 9999) . "','" . $data . "')";
        $this->query($query);
        if ($this->affected_rows > 0) {
            $this->logAction('INSERT-PERSONAL', $query, $_SESSION['username']);
            return TRUE;
        }
        echo $this->error;
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @param $an
     * @param $luna
     * @return mixed
     */
    public function getIndexStartAparate($idLocatie, $an, $luna)
    {
        $query = "SELECT
              a.idAparat,
              a.idxInM,
              a.idxOutM,
              a.seria
              FROM $this->database.aparate a
              INNER JOIN $this->database.locatii l ON a.idLocatie = l.idlocatie WHERE a.idLocatie='$idLocatie' AND a.dtBlocare < '$an-$luna-31'";
        $result = $this->query($query);
        while ($obj = $result->fetch_object()) {
            $idx[$obj->idAparat] = $obj;
        }
        return $idx;
    }

    /**
     * @param $idAparat
     * @param $seria
     * @param $user
     * @param $postPic
     * @return bool
     */
    public function insertAudit($idAparat, $seria, $user, $postPic)
    {
        $data = date('Y-m-d H:i:s');
        $query = "INSERT INTO $this->database.auditpic
              (idAparat,idPers,seria,postPic,data)
              VALUES
              ('$idAparat','$user','$seria','$postPic','$data')";
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @param $an
     * @param $luna
     * @return mixed
     */
    public function getNrAparateByDate($idLocatie, $an, $luna)
    {

        $query = "SELECT count(idAparat) AS nr_aparate FROM $this->database.aparate WHERE ";
        $query .= "idLocatie=$idLocatie AND (aparate.dtBlocare='1000-01-01' OR aparate.dtBlocare >= '$an-$luna-01') AND aparate.dtActivare <= '$an-$luna-31'";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->nr_aparate;
    }

    /**
     * @param $idLocatie
     * @return mixed
     */
    public function getResponsabilId($idLocatie)
    {
        $query = "SELECT idresp FROM $this->database.locatii WHERE idlocatie=$idLocatie";
        $result = $this->query($query);
        $obj = $result->fetch_object();
        return $obj->idresp;
    }

    /**
     * @return array
     */
    public function getStareAparate()
    {
        $aparate = [];
        $query = "SELECT stareaparate.ultimaConectare,
                         locatii.idOperator,
                         locatii.idLocatie,
                         locatii.idresp,
                         aparate.pozitieLocatie,
                         TIMESTAMPDIFF(MINUTE,stareaparate.ultimaConectare,NOW())
                  FROM  $this->database.stareaparate
                  INNER JOIN $this->database.aparate ON aparate.idAparat = stareaparate.idAparat
                  INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie
                  WHERE dtblocare = '1000-01-01' ORDER BY aparate.pozitieLocatie ASC;";
        $result = $this->query($query);
        echo $this->error;
        while ($aparat = $result->fetch_object()) {
            $aparate[] = $aparat;
        }
        return $aparate;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function verificaErroriIndex($idLocatie)
    {
        $aparate = [];
        $query = "SELECT e.idAparat, count(e.idAparat) as nrErori FROM $this->database.errorpk e WHERE e.idLocatie=$idLocatie GROUP BY e.idAparat;";
        $result = $this->query($query);
        while ($aparat = $result->fetch_object()) {
            $aparate[$aparat->idAparat] = $aparat;
        }
        return $aparate;
    }

    /**
     * @param null $idAparat
     * @return array
     */
    public function getErori($idAparat = null)
    {
        $erori = [];
        $query = "SELECT er.idpachet, er.serieAparat, er.idAparat, er.idOperator, er.idLocatie, er.indexInM, "
            . "er.indexOutM, er.idxInMB, er.idxOutMB, er.ip, ap.pozitieLocatie, er.dataServer,er.exceptia, er.indexInE, er.indexOutE, er.idxInEB, er.idxOutEB,"
            . "st.verSoft FROM $this->database.errorpk er "
            . "inner join $this->database.aparate ap on er.idaparat=ap.idaparat "
            . "INNER join $this->database.stareaparate st ON st.idAparat=ap.idAparat";
        if ($idAparat != null) {
            $query .= " WHERE  er.idAparat=$idAparat ";
        }
        $query .= " order by er.idpachet desc";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            while ($eroare = $result->fetch_object()) {
                $erori[] = $eroare;
            }
        }
        return $erori;
    }

    /**
     * @param null $idAparat
     * @return bool
     */
    public function deleteErors($idAparat = null)
    {
        $query = "DELETE FROM $this->database.errorpk ";
        if ($idAparat != null) {
            $query .= " WHERE idAparat=$idAparat;";
        }
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @param $infoRooter
     * @return bool
     */
    public function salveazaInformatiiRooter($idLocatie, $infoRooter)
    {
        $query = "UPDATE $this->database.locatii SET contractInternet='$infoRooter'  WHERE idlocatie=$idLocatie";
        $this->query($query);
        if ($this->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $domeniu
     * @param $an
     * @return array
     */
    public function getAvertizariTable($domeniu, $an)
    {
        $avertizari = [];
        $query = "SELECT 
                avertizari.dtExpMetrologie,
                avertizari.dtExpAutorizatie,
                aparate.seria,
                aparate.tip,
                personal.nick,
                locatii.denumire,
                locatii.idOperator
            FROM $this->database.avertizari
            INNER JOIN $this->database.aparate ON aparate.idAparat = avertizari.idAparat
            INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie
            INNER JOIN $this->database.personal ON personal.idpers = locatii.idresp
            WHERE aparate.dtBlocare = '1000-01-01' AND extract(year FROM $domeniu) = $an  ORDER BY locatii.idResp ASC, locatii.denumire ASC";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            while ($avertizare = $result->fetch_object()) {
                $avertizari[] = $avertizare;
            }
        }
        return $avertizari;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function getNetByLocation($idLocatie)
    {
        $links = [];
        $query = "SELECT * FROM $this->database.net WHERE idLoc=$idLocatie";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $links[$obj->tip] = $obj;
            }
        }
        return $links;
    }

    /**
     * @param $idLocatie
     * @param $port
     * @param $tip
     * @param $user
     * @param $pass
     * @return bool
     */
    public function insertIntoNet($idLocatie, $port, $tip, $user, $pass)
    {
        $data = date('Y-m-d');
        $query = "INSERT INTO $this->database.net (idLoc,tip,port,valUser,valPass,data) VALUES ('$idLocatie','$tip','$port','$user','$pass','$data')";
        $this->query($query);
        if ($this->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $idNet
     * @param $port
     * @param $user
     * @param $pass
     * @return bool
     */
    public function updateIntoNet($idNet, $port, $user, $pass)
    {
        $data = date('Y-m-d');
        $query = "UPDATE $this->database.net SET port='$port', valUser='$user', valPass='$pass', data='$data' WHERE idNet=$idNet";
        $this->query($query);
        if ($this->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * MUTAT
     * @param $idAparat
     * @return object|stdClass
     */
    public function getAparatInfo($idAparat)
    {
        $query = "SELECT 
                    aparate.idxInM,
                    aparate.idxOutM,
                    aparate.seria,
                    aparate.dtActivare,
                    aparate.dtBlocare,
                    locatii.denumire,
                    locatii.idlocatie,
                    personal.nick 
                FROM $this->database.aparate
                INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie
                INNER JOIN $this->database.personal ON locatii.idresp = personal.idpers
                WHERE aparate.idAparat = $idAparat";
        $result = $this->query($query);
        $aparat = $result->fetch_object();
        return $aparat;
    }

	/**
     *  MUTAT
     *  
     * @param $idAparat
     * @param $an
     * @param $luna
     * @return array
     */
    public function getContoriAparat($idAparat, $an, $luna)
    {
        $indexi = [];
		$data = $an."-".$luna."-01";
		$luna2 = date("m",strtotime($data));
        $query = "SELECT "
            . "cm.idmec, "
            . "cm.idxInM, "
            . "cm.idxOutM, "
			. "cm.idAparat, "
            . "cm.dtServer, "
			. "cm.cashIn AS cashInM, "
            . "cm.cashOut AS cashOutM, "
            . "cm.castig AS castigM, "
			. "ce.idAparat, "
			. "ce.cashIn AS cashInE, "
            . "ce.cashOut AS cashOutE, "
            . "ce.castig AS castigE, "
            . "ce.idel, "
            . "ce.idxInE, "
            . "ce.idxOutE, "
            . "ce.dtServer AS ce_dtServer, "
			. "ep.idAparat, "
			. "ep.indexInM, "
            . "ep.indexOutM, "
            . "ep.indexInE, "
            . "ep.indexOutE, "
            . "ep.dataServer, "
			. "rc.idaparat, "
            . "SUM(rc.idxInE) AS idxineSUM, "
            . "SUM(rc.idxOutE) AS idxouteSUM "
            . "FROM $this->database.contormecanic$an$luna cm "
            . "LEFT JOIN $this->database.contorelectronic$an$luna ce ON DATE(ce.dtServer)=DATE(cm.dtServer) "
		    . "AND cm.idAparat=ce.idAparat "
			. "LEFT JOIN $this->database.errorpk ep ON DATE(ce.dtServer) = DATE(ep.dataServer) AND cm.idAparat = ep.idAparat "
			. "LEFT JOIN $this->database.resetcontori rc ON rc.idaparat = cm.idAparat AND DATE(rc.dtInitiere) = DATE(cm.dtServer) "
            . "WHERE cm.dtServer like '$an-$luna2%' AND cm.idAparat='{$idAparat}' GROUP BY cm.dtServer ORDER BY cm.dtServer ASC";
		$result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $indexi[] = $obj;
            }
        }
        return $indexi;
    }
	
    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @return array
     */
    public function getIndexiCMAparat($idAparat, $an, $luna)
    {
        $indexi = [];
        $query = "SELECT "
            . "cm.idmec,"
            . "cm.idxInM, "
            . "cm.idxOutM, "
            . "cm.dtServer,"
            . "cm.cashIn, "
            . "cm.cashOut,"
            . "cm.castig, "
            . "aparate.dtBlocare,"
            . "aparate.dtActivare, "
            . "personal.nick "
            . "FROM $this->database.contormecanic$an$luna cm "
            . "INNER JOIN $this->database.aparate ON aparate.idAparat = cm.idAparat "
            . "INNER JOIN $this->database.personal ON cm.setatDe = personal.idpers "
            . " WHERE cm.idAparat='{$idAparat}' ORDER BY cm.dtServer ASC";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                $indexi[] = $obj;
            }
        }
        return $indexi;
    }

    /**
     * @param $idmec
     * @param $idxIn
     * @param $idxOut
     * @param $an
     * @param $luna
     * @param $user
     * @return bool
     */
    public function updateContoriById($idmec, $idxIn, $idxOut, $an, $luna, $user)
    {
        $query = "UPDATE $this->database.contormecanic$an$luna SET idxInM = $idxIn, idxOutM= $idxOut, setatDe='$user' WHERE idmec=$idmec";
        $this->query($query);
        if ($this->affected_rows > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param $idAparat
     * @param $valoare
     * @return bool
     */
    public function updatePozitieAparat($idAparat, $valoare)
    {
        $query = "UPDATE $this->database.aparate SET pozitieLocatie=$valoare WHERE idAparat=$idAparat";
        $this->query($query);
        if ($this->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $idPers
     * @param null $idOperator
     * @return array
     */
    public function getErrorsByPers($idPers, $idOperator = null)
    {
        $aparate = [];
        $query = "SELECT 
                    aparate.idAparat, 
                    aparate.idLocatie,
                    count(e.idAparat) as nrErori
                FROM $this->database.aparate 
                LEFT JOIN $this->database.errorpk e ON e.idAparat = aparate.idAparat
                INNER JOIN $this->database.locatii ON aparate.idlocatie = locatii.idLocatie
                WHERE locatii.idresp=$idPers AND aparate.dtBlocare='1000-01-01' ";
        if ($idOperator != '') {
            $query .= "AND locatii.idOperator=$idOperator ";
        }
        $query .= " GROUP BY aparate.idAparat ORDER BY aparate.pozitieLocatie ASC";
        $result = $this->query($query);
        if ($result) {
            while ($aparat = $result->fetch_object()) {
                $aparate[$aparat->idLocatie][$aparat->idAparat] = $aparat->nrErori;
            }
        }
        return $aparate;
    }

    /**
     *
     */
    public function ínsertLocatiiNotInNet()
    {
        $locatii = [];
        $query = "SELECT idlocatie FROM $this->database.locatii WHERE idlocatie not in (SELECT distinct idloc FROM $this->database.net)";
        $result = $this->query($query);
        while ($locatie = $result->fetch_object()) {
            $this->insertIntoNet($locatie->idlocatie, '1', '1', 'admin', 'RealBet77');
            $this->insertIntoNet($locatie->idlocatie, '81', '2', 'admin', 'RealBet88');
            $this->insertIntoNet($locatie->idlocatie, '82', '3', 'admin', '...');
        }
    }

    /**
     * @return array
     */
    public function getAudite()
    {
        $audite = [];
        $query = "SELECT "
            . "auditpic.idAparat,"
            . "auditpic.seria,"
            . "auditpic.postPic,"
            . "auditpic.data,"
            . " personal.nick "
            . "FROM $this->database.auditpic
                INNER JOIN $this->database.personal ON auditpic.idPers = personal.idpers ORDER by auditpic.data DESC;";
        $result = $this->query($query);
        if ($result) {
            while ($audit = $result->fetch_object()) {
                $audite[] = $audit;
            }
        }
        return $audite;
    }

    /**
     * @param $tip
     * @return array
     */
    public function getDistinctAni($tip)
    {
        $ani = [];
        $query = "SELECT extract(year from $tip) as ani FROM $this->database.avertizari
                INNER JOIN $this->database.aparate ON aparate.idAparat = avertizari.idAparat  WHERE aparate.dtBlocare='1000-01-01' GROUP BY ani";
        $result = $this->query($query);
        if ($result) {
            while ($an = $result->fetch_object()) {
                $ani[] = $an->ani;
            }
        }
        return $ani;
    }

    /**
     * @param $idLocatie
     * @param $an
     * @param $luna
     * @return array
     */
    public function getCastiguriLocatii($idLocatie, $an, $luna)
    {
        $locatii = [];
        $query = "SELECT 
                aparate.idAparat,
                aparate.seria,
                cm.castig,
                cm.dtServer,
                cm.nrPacWan,
                cm.nrPac3g,
                extract(day from dtServer) as zi,
                extract(month from dtServer) as luna,
                extract(year from dtserver) as an
            FROM $this->database.contormecanic$an$luna cm
            INNER JOIN $this->database.aparate ON aparate.idLocatie = locatii.idlocatie
            WHERE cm.idlocatie=$idLocatie";
        $result = $this->query($query);
        if ($result) {
            while ($obj = $result->fetch_object()) {
                echo $obj->idAparat . ' = > ' . $obj->seria . '<br/>';
                $locatii[$obj->denumire][$obj->zi][$obj->idAparat] = $obj;
            }
        }
        return $locatii;
    }

    /**
     * @param $idAparat
     * @param $luna
     * @param $an
     */
    public function getNrPac($idAparat, $luna, $an)
    {
        $query = "SELECT 
                    nrPac3g,
                    nrPacWan 
                FROM $this->database.contormecanic$an$luna 
                WHERE idAparat=$idAparat AND dtServer IN (SELECT max(dtServer) FROM $this->database.contormecanic$an$luna WHERE idAparat=$idAparat) ";
    }

    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return object|stdClass|string
     */
    public function getPacheteAparat($idAparat, $an, $luna, $zi)
    {
        $obj = '';
        if ($zi != 0) {
            $query = "SELECT 
                cm.nrPacWan,
                cm.nrPac3g 
            FROM $this->database.contormecanic$an$luna cm
            WHERE cm.dtServer BETWEEN '$an-$luna-$zi 00:00:00' AND '$an-$luna-$zi 23:59:59' AND idAparat=$idAparat";
        } else {
            $query = "SELECT 
                cm.nrPacWan,
                cm.nrPac3g 
            FROM $this->database.contormecanic$an$luna cm
            WHERE cm.dtServer in (SELECT min(cm2.dtServer) FROM $this->database.contormecanic$an$luna cm2 WHERE cm2.idAparat=$idAparat);";
        }
        $result = $this->query($query);
        if ($result) {
            $obj = $result->fetch_object();
        }
        return $obj;
    }

    /**
     * @param $an
     * @param $luna
     * @param $idLocatie
     * @return array
     */
    public function getCastigAparate($an, $luna, $idLocatie)
    {
        $aparate = [];
        $query = "SELECT 
                aparate.idAparat,
                ifnull(cm.castig,0) as castig,
                extract(day from cm.dtServer) as zi,
                extract(month from cm.dtServer) as luna,
                extract(year from cm.dtServer) as an,
                cm.nrPacWan,
                cm.nrPac3g,
                aparate.seria
            FROM $this->database.aparate  
            RIGHT JOIN $this->database.contormecanic$an$luna cm on aparate.idAparat = cm.idAparat
            WHERE cm.idLocatie =$idLocatie and (aparate.dtBlocare='1000-01-01' OR aparate.dtBlocare >= '$an-$luna-0') AND dtActivare <='$an-$luna-31' order BY idAparat asc, zi asc";
        $result = $this->query($query);
        if ($result) {
            while ($aparat = $result->fetch_object()) {
                $aparate[$aparat->idAparat][$aparat->zi] = $aparat;
            }
        }
        return $aparate;
    }

    /**
     * @param $idLocatie
     * @param $an
     * @param $luna
     * @return int
     */
    public function getJackPot($idLocatie, $an, $luna)
    {
        $query = "SELECT 
                    SUM(jack_pot) as jackpot 
                  FROM $this->database.jackpot 	
                  WHERE idLocatie = $idLocatie AND data > '$an-".($luna < 10 ? 0 : "")."$luna-00' AND data < '$an-".($luna<10 ? 0 : "")."$luna-32'";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            $data = $result->fetch_object();
            return $data->jackpot;
        }
        return 0;
    }

    /**
     * @return array
     */
    public function getAparateCuPic()
    {
        $aparateCu = [];
        $query = "SELECT 
                    p.nick,
                    l.idOperator,
                    l.denumire,
                    l.idlocatie,
                    a.seria,
                    a.idAparat,
                    sa.ipPic,
                    sa.ipPic3g,
                    sa.verSoft,
                    sa.lastIdxInM,
                    sa.lastIdxOutM,
                    sa.ultimaConectare
                FROM $this->database.stareaparate sa
                INNER JOIN $this->database.aparate a ON a.idAparat = sa.idAparat
                INNER JOIN $this->database.locatii l ON a.idlocatie = l.idlocatie
                INNER JOIN $this->database.personal p ON p.idpers = l.idresp 
                WHERE a.dtBlocare = '1000-01-01' order by idOperator asc,p.idpers ASC,idLocatie desc;";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            while ($data = $result->fetch_object()) {
                $aparateCu[] = $data;
            }
        }
        return $aparateCu;
    }

    /**
     * @return mixed
     */
    private function getCon()
    {
        return $this->con;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return int
     */
    public function getAparateUltimaOra()
    {
        $an = date('Y');
        $luna = date('m');
        $zi = date('d');
        $ora = date('H') - 1;
        $minutul = date('m');
        $qeury = "SELECT count(idAparat) as nrAparate FROM brunersrl.stareaparate WHERE ultimaConectare>'$an-$luna-$zi $ora:$minutul'";
        $result = $this->query($qeury);
        if ($this->affected_rows > 0) {
            $data = $result->fetch_object();
            return $data->nrAparate;
        }
        return 0;
    }

    /**
     * @param $an
     * @param $luna
     * @param $idResponsabil
     * @return array
     */
    public function getCastigPeZileLocatii($an, $luna, $idResponsabil)
    {
        $resultSet = [];
        $query = "SELECT
                    locatii.denumire,
                    locatii.idlocatie,
                    SUM(castig) as castig,
                    extract(day from dtServer) as zi,
                    locatii.idLocatie
                 FROM $this->database.contormecanic$an$luna cm RIGHT JOIN $this->database.locatii  ON cm.idLocatie = locatii.idLocatie WHERE locatii.idResp={$idResponsabil}
                 GROUP BY cm.idLocatie, zi ORDER BY dtServer ASC;";
        $result = $this->query($query);
        echo $this->error;
        if ($this->affected_rows > 0) {
            while ($data = $result->fetch_object()) {
                $resultSet[$data->idlocatie][$data->zi] = $data;
            }
        }
        return $resultSet;
    }

    /**
     * @param $tip
     * @param $an
     * @param $luna
     * @param $operator
     * @return array
     */
    public function getAvertizariByLuna($tip, $an, $luna, $operator)
    {
        $expirari = [];
        $query = "SELECT
                avertizari.dtExpMetrologie,
                avertizari.dtExpAutorizatie,
                aparate.seria,
                aparate.tip,
                personal.nick,
                personal.idpers,
                locatii.denumire,
                locatii.idOperator,
                locatii.regiune,
                locatii.adresa
            FROM $this->database.avertizari
            INNER JOIN $this->database.aparate ON aparate.idAparat = avertizari.idAparat
            INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie
            INNER JOIN $this->database.personal ON personal.idpers = locatii.idresp
            WHERE aparate.dtBlocare = '1000-01-01' AND (extract(MONTH FROM $tip) = $luna AND extract(YEAR FROM $tip) =$an) AND locatii.idOperator=$operator ORDER BY personal.nick ASC, locatii.idlocatie ASC";
        $result = $this->query($query);
        echo $this->error;
        if ($this->affected_rows > 0) {
            while ($row = $result->fetch_object()) {
                $expirari[] = $row;
            }
        }
        return $expirari;
    }

    /**
     * @return array
     */
    public function getResponsabili()
    {
        $responsabili = [];
        $sql = "SELECT personal.idpers,personal.nick FROM $this->database.personal WHERE personal.idpers IN
                    (SELECT DISTINCT locatii.idresp FROM $this->database.locatii);";
        $result = $this->query($sql);
        if ($this->affected_rows > 0) {
            while ($row = $result->fetch_object()) {
                $responsabili[] = $row;
            }
        }
        return $responsabili;
    }

    /**
     * @param $idResponsabil
     * @return int
     */
    public function getNrEroriResponsabil($idResponsabil)
    {
        $sql = "SELECT count(distinct locatii.idlocatie) as nrErori FROM $this->database.errorpk
                INNER JOIN $this->database.aparate ON aparate.idAparat = errorpk.idAparat
                INNER JOIN $this->database.locatii ON locatii.idlocatie = aparate.idLocatie
                WHERE locatii.idresp = $idResponsabil";
        $result = $this->query($sql);
        if(is_bool($result)){
            echo $this->error;
        }
        $row = $result->fetch_object();

        return $row->nrErori;
    }
    /**
     * @return array
     */
    public function getArrayErroriResponsabil()
    {
        $erori = [];
        $responsabili = $this->getResponsabili();

        foreach ($responsabili as $responsabil) {
            $erori[$responsabil->nick] = $this->getNrEroriResponsabil($responsabil->idpers);
        }

        return $erori;
    }

    /**
     * @return array
     */
    public function returneazaTransferuri($data_start, $data_end){
        $transferuri = [];
        $query = "SELECT * FROM $this->database.transferaparate WHERE transferaparate.dtBaza BETWEEN '".$data_start."' AND '".$data_end."' ORDER BY transferaparate.idtransfer DESC";
        $result = $this->query($query);
        if($this->affected_rows > 0) {
            while($row = $result->fetch_assoc()){
                $transferuri[] = $row;
            }
        }
        return $transferuri;
    }
    /**
     * @return array
     */
    public function returneazaTransferuriOperatori($data_start, $data_end, $idOperator){
        $transferuri = [];
        $query = "SELECT * FROM transferaparate, locatii WHERE (transferaparate.dtBaza BETWEEN '".$data_start."' AND '".$data_end."') AND locatii.idlocatie = transferaparate.idLocInainte AND locatii.idOperator = {$idOperator} ORDER BY transferaparate.idtransfer DESC";
        $result = $this->query($query);
        if($this->affected_rows > 0) {
            while($row = $result->fetch_assoc()){
                $transferuri[] = $row;
            }
        }
        return $transferuri;
    }
    /**
    *functie pentru afisare an transfer
    */
    public function getTransferAni($tip)
    {
        $ani = [];
        $query = "SELECT extract(year from $tip) as ani FROM $this->database.transferaparate
                INNER JOIN $this->database.aparate ON aparate.idAparat = transferaparate.idApInainte GROUP BY ani ORDER BY ani DESC";
        $result = $this->query($query);
        if ($result) {
            while ($an = $result->fetch_object()) {
                $ani[] = $an->ani;
            }
        }
        return $ani;
    }
    
    /**
     * Returneaza toate aparatele din baza de date
     */
    public function getAllAparate($idOperator)
    {
        $aparate = [];
        $query = "SELECT *,locatii.adresa FROM $this->database.aparate
        INNER JOIN $this->database.locatii  ON locatii.idlocatie = aparate.idLocatie
        INNER JOIN $this->database.stareaparate ON stareaparate.idAparat = aparate.idAparat
        INNER JOIN $this->database.firme  ON firme.idfirma = locatii.idfirma
        WHERE aparate.dtBlocare='1000-01-01' AND locatii.idOperator={$idOperator}";
        $result = $this->query($query);
        if ($this->affected_rows > 0) {
            while ($row = $result->fetch_object()) {
                $aparate[] = $row;
            }
        }
        return $aparate;
    }


}

$db = new dbFull('localhost', 'root', '','brunersrl');
