<?php

class StareAparatEntity
{
    public $idAparat;
    public $ipPic;
    public $ipPic3g;
    public $verSoft;
    public $bitiStare;
    public $t12V;
    public $t5V;
    public $t3V3V;
    public $tBat;
    public $bitiComanda;
    public $difIn;
    public $difOut;
    public $timpOff;
    public $timpPachet1;
    public $timpPachet2;
    public $adrPachet1;
    public $adrPachet2;
    public $adrPachet3;
    public $hostNamePic;
    public $userPic;
    public $passPic;
    public $stareRetur;
    public $lastIdxInM;
    public $lastIdxOutM;
    public $lastIdxBetM;
    public $lastIdxWinM;
    public $lastIdxGamesM;
    public $dtLastM;
    public $lastIdxInE;
    public $lastIdxOutE;
    public $lastIdxBetE;
    public $dtLastE;
    public $ultimaConectare;
    public $dtIpPic;
    public $idApRetur;
    public $idxInMRet;
    public $idxOutMRet;
    public $idxBetMRet;
    public $oreDeLaUltimultPachet;
    public $macPic;
    /** @var  SessionClass */
    protected $appSettings;
    /** @var  DataConnection */
    protected $db;

    public function __construct(DataConnection $db, SessionClass $appSettings)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettings);
    }

    /**
     * @return mixed
     */
    public function getOreDeLaUltimultPachet()
    {
        return $this->oreDeLaUltimultPachet;
    }

    /**
     * @param mixed $oreDeLaUltimultPachet
     */
    public function setOreDeLaUltimultPachet($oreDeLaUltimultPachet)
    {
        $this->oreDeLaUltimultPachet = $oreDeLaUltimultPachet;
    }



    /**
     * @return SessionClass
     */
    public function getAppSettings()
    {
        return $this->appSettings;
    }

    /**
     * @param SessionClass $appSettings
     */
    public function setAppSettings($appSettings)
    {
        $this->appSettings = $appSettings;
    }

    /**
     * @return DataConnection
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DataConnection $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }


    /**
     * @return mixed
     */
    public function getAdrPachet1()
    {
        return $this->adrPachet1;
    }

    /**
     * @param mixed $adrPachet1
     */
    public function setAdrPachet1($adrPachet1)
    {
        $this->adrPachet1 = $adrPachet1;
    }

    /**
     * @return mixed
     */
    public function getAdrPachet2()
    {
        return $this->adrPachet2;
    }

    /**
     * @param mixed $adrPachet2
     */
    public function setAdrPachet2($adrPachet2)
    {
        $this->adrPachet2 = $adrPachet2;
    }

    /**
     * @return mixed
     */
    public function getAdrPachet3()
    {
        return $this->adrPachet3;
    }

    /**
     * @param mixed $adrPachet3
     */
    public function setAdrPachet3($adrPachet3)
    {
        $this->adrPachet3 = $adrPachet3;
    }

    /**
     * @return mixed
     */
    public function getBitiComanda()
    {
        return $this->bitiComanda;
    }

    /**
     * @param mixed $bitiComanda
     */
    public function setBitiComanda($bitiComanda)
    {
        $this->bitiComanda = $bitiComanda;
    }

    /**
     * @return mixed
     */
    public function getBitiStare()
    {
        return $this->bitiStare;
    }

    /**
     * @param mixed $bitiStare
     */
    public function setBitiStare($bitiStare)
    {
        $this->bitiStare = $bitiStare;
    }

    /**
     * @return mixed
     */
    public function getDifIn()
    {
        return $this->difIn;
    }

    /**
     * @param mixed $difIn
     */
    public function setDifIn($difIn)
    {
        $this->difIn = $difIn;
    }

    /**
     * @return mixed
     */
    public function getDifOut()
    {
        return $this->difOut;
    }

    /**
     * @param mixed $difOut
     */
    public function setDifOut($difOut)
    {
        $this->difOut = $difOut;
    }

    /**
     * @return mixed
     */
    public function getDtIpPic()
    {
        return $this->dtIpPic;
    }

    /**
     * @param mixed $dtIpPic
     */
    public function setDtIpPic($dtIpPic)
    {
        $this->dtIpPic = $dtIpPic;
    }

    /**
     * @return mixed
     */
    public function getDtLastE()
    {
        return $this->dtLastE;
    }

    /**
     * @param mixed $dtLastE
     */
    public function setDtLastE($dtLastE)
    {
        $this->dtLastE = $dtLastE;
    }

    /**
     * @return mixed
     */
    public function getDtLastM()
    {
        return $this->dtLastM;
    }

    /**
     * @param mixed $dtLastM
     */
    public function setDtLastM($dtLastM)
    {
        $this->dtLastM = $dtLastM;
    }

    /**
     * @return mixed
     */
    public function getHostNamePic()
    {
        return $this->hostNamePic;
    }

    /**
     * @param mixed $hostNamePic
     */
    public function setHostNamePic($hostNamePic)
    {
        $this->hostNamePic = $hostNamePic;
    }

    /**
     * @return mixed
     */
    public function getIdAparat()
    {
        return $this->idAparat;
    }

    /**
     * @param mixed $idAparat
     */
    public function setIdAparat($idAparat)
    {
        $this->idAparat = $idAparat;
    }

    /**
     * @return mixed
     */
    public function getIdApRetur()
    {
        return $this->idApRetur;
    }

    /**
     * @param mixed $idApRetur
     */
    public function setIdApRetur($idApRetur)
    {
        $this->idApRetur = $idApRetur;
    }

    /**
     * @return mixed
     */
    public function getIdxBetMRet()
    {
        return $this->idxBetMRet;
    }

    /**
     * @param mixed $idxBetMRet
     */
    public function setIdxBetMRet($idxBetMRet)
    {
        $this->idxBetMRet = $idxBetMRet;
    }

    /**
     * @return mixed
     */
    public function getIdxInMRet()
    {
        return $this->idxInMRet;
    }

    /**
     * @param mixed $idxInMRet
     */
    public function setIdxInMRet($idxInMRet)
    {
        $this->idxInMRet = $idxInMRet;
    }

    /**
     * @return mixed
     */
    public function getIdxOutMRet()
    {
        return $this->idxOutMRet;
    }

    /**
     * @param mixed $idxOutMRet
     */
    public function setIdxOutMRet($idxOutMRet)
    {
        $this->idxOutMRet = $idxOutMRet;
    }

    /**
     * @return mixed
     */
    public function getIpPic()
    {
        return $this->ipPic;
    }

    /**
     * @param mixed $ipPic
     */
    public function setIpPic($ipPic)
    {
        $this->ipPic = $ipPic;
    }

    /**
     * @return mixed
     */
    public function getIpPic3g()
    {
        return $this->ipPic3g;
    }

    /**
     * @param mixed $ipPic3g
     */
    public function setIpPic3g($ipPic3g)
    {
        $this->ipPic3g = $ipPic3g;
    }

    /**
     * @return mixed
     */
    public function getLastIdxBetE()
    {
        return $this->lastIdxBetE;
    }

    /**
     * @param mixed $lastIdxBetE
     */
    public function setLastIdxBetE($lastIdxBetE)
    {
        $this->lastIdxBetE = $lastIdxBetE;
    }

    /**
     * @return mixed
     */
    public function getLastIdxBetM()
    {
        return $this->lastIdxBetM;
    }

    /**
     * @param mixed $lastIdxBetM
     */
    public function setLastIdxBetM($lastIdxBetM)
    {
        $this->lastIdxBetM = $lastIdxBetM;
    }

    /**
     * @return mixed
     */
    public function getLastIdxGamesM()
    {
        return $this->lastIdxGamesM;
    }

    /**
     * @param mixed $lastIdxGamesM
     */
    public function setLastIdxGamesM($lastIdxGamesM)
    {
        $this->lastIdxGamesM = $lastIdxGamesM;
    }

    /**
     * @return mixed
     */
    public function getLastIdxInE()
    {
        return $this->lastIdxInE;
    }

    /**
     * @param mixed $lastIdxInE
     */
    public function setLastIdxInE($lastIdxInE)
    {
        $this->lastIdxInE = $lastIdxInE;
    }

    /**
     * @return mixed
     */
    public function getLastIdxInM()
    {
        return $this->lastIdxInM;
    }

    /**
     * @param mixed $lastIdxInM
     */
    public function setLastIdxInM($lastIdxInM)
    {
        $this->lastIdxInM = $lastIdxInM;
    }

    /**
     * @return mixed
     */
    public function getLastIdxOutE()
    {
        return $this->lastIdxOutE;
    }

    /**
     * @param mixed $lastIdxOutE
     */
    public function setLastIdxOutE($lastIdxOutE)
    {
        $this->lastIdxOutE = $lastIdxOutE;
    }

    /**
     * @return mixed
     */
    public function getLastIdxOutM()
    {
        return $this->lastIdxOutM;
    }

    /**
     * @param mixed $lastIdxOutM
     */
    public function setLastIdxOutM($lastIdxOutM)
    {
        $this->lastIdxOutM = $lastIdxOutM;
    }

    /**
     * @return mixed
     */
    public function getLastIdxWinM()
    {
        return $this->lastIdxWinM;
    }

    /**
     * @param mixed $lastIdxWinM
     */
    public function setLastIdxWinM($lastIdxWinM)
    {
        $this->lastIdxWinM = $lastIdxWinM;
    }

    /**
     * @return mixed
     */
    public function getMacPic()
    {
        return $this->macPic;
    }

    /**
     * @param mixed $macPic
     */
    public function setMacPic($macPic)
    {
        $this->macPic = $macPic;
    }

    /**
     * @return mixed
     */
    public function getPassPic()
    {
        return $this->passPic;
    }

    /**
     * @param mixed $passPic
     */
    public function setPassPic($passPic)
    {
        $this->passPic = $passPic;
    }

    /**
     * @return mixed
     */
    public function getStareRetur()
    {
        return $this->stareRetur;
    }

    /**
     * @param mixed $stareRetur
     */
    public function setStareRetur($stareRetur)
    {
        $this->stareRetur = $stareRetur;
    }

    /**
     * @return mixed
     */
    public function getT12V()
    {
        return $this->t12V;
    }

    /**
     * @param mixed $t12V
     */
    public function setT12V($t12V)
    {
        $this->t12V = $t12V;
    }

    /**
     * @return mixed
     */
    public function getT3V3V()
    {
        return $this->t3V3V;
    }

    /**
     * @param mixed $t3V3V
     */
    public function setT3V3V($t3V3V)
    {
        $this->t3V3V = $t3V3V;
    }

    /**
     * @return mixed
     */
    public function getT5V()
    {
        return $this->t5V;
    }

    /**
     * @param mixed $t5V
     */
    public function setT5V($t5V)
    {
        $this->t5V = $t5V;
    }

    /**
     * @return mixed
     */
    public function getTBat()
    {
        return $this->tBat;
    }

    /**
     * @param mixed $tBat
     */
    public function setTBat($tBat)
    {
        $this->tBat = $tBat;
    }

    /**
     * @return mixed
     */
    public function getTimpOff()
    {
        return $this->timpOff;
    }

    /**
     * @param mixed $timpOff
     */
    public function setTimpOff($timpOff)
    {
        $this->timpOff = $timpOff;
    }

    /**
     * @return mixed
     */
    public function getTimpPachet1()
    {
        return $this->timpPachet1;
    }

    /**
     * @param mixed $timpPachet1
     */
    public function setTimpPachet1($timpPachet1)
    {
        $this->timpPachet1 = $timpPachet1;
    }

    /**
     * @return mixed
     */
    public function getTimpPachet2()
    {
        return $this->timpPachet2;
    }

    /**
     * @param mixed $timpPachet2
     */
    public function setTimpPachet2($timpPachet2)
    {
        $this->timpPachet2 = $timpPachet2;
    }

    /**
     * @return mixed
     */
    public function getUltimaConectare()
    {
        return $this->ultimaConectare;
    }

    /**
     * @param mixed $ultimaConectare
     */
    public function setUltimaConectare($ultimaConectare)
    {
        $this->ultimaConectare = $ultimaConectare;
    }

    /**
     * @return mixed
     */
    public function getUserPic()
    {
        return $this->userPic;
    }

    /**
     * @param mixed $userPic
     */
    public function setUserPic($userPic)
    {
        $this->userPic = $userPic;
    }

    /**
     * @return mixed
     */
    public function getVerSoft()
    {
        return $this->verSoft;
    }

    /**
     * @param mixed $verSoft
     */
    public function setVerSoft($verSoft)
    {
        $this->verSoft = $verSoft;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->ipPic = isset($data['ipPic']) ? $data['ipPic'] : NULL;
        $this->ipPic3g = isset($data['ipPic3g']) ? $data['ipPic3g'] : NULL;
        $this->verSoft = isset($data['verSoft']) ? $data['verSoft'] : NULL;
        $this->bitiStare = isset($data['bitiStare']) ? $data['bitiStare'] : NULL;
        $this->t12V = isset($data['t12V']) ? $data['t12V'] : NULL;
        $this->t5V = isset($data['t5V']) ? $data['t5V'] : NULL;
        $this->t3V3V = isset($data['t3V3V']) ? $data['t3V3V'] : NULL;
        $this->tBat = isset($data['tBat']) ? $data['tBat'] : NULL;
        $this->bitiComanda = isset($data['bitiComanda']) ? $data['bitiComanda'] : NULL;
        $this->difIn = isset($data['difIn']) ? $data['difIn'] : NULL;
        $this->difOut = isset($data['difOut']) ? $data['difOut'] : NULL;
        $this->timpOff = isset($data['timpOff']) ? $data['timpOff'] : NULL;
        $this->timpPachet1 = isset($data['timpPachet1']) ? $data['timpPachet1'] : NULL;
        $this->timpPachet2 = isset($data['timpPachet2']) ? $data['timpPachet2'] : NULL;
        $this->adrPachet1 = isset($data['adrPachet1']) ? $data['adrPachet1'] : NULL;
        $this->adrPachet2 = isset($data['adrPachet2']) ? $data['adrPachet2'] : NULL;
        $this->adrPachet3 = isset($data['adrPachet3']) ? $data['adrPachet3'] : NULL;
        $this->hostNamePic = isset($data['hostNamePic']) ? $data['hostNamePic'] : NULL;
        $this->userPic = isset($data['userPic']) ? $data['userPic'] : NULL;
        $this->passPic = isset($data['passPic']) ? $data['passPic'] : NULL;
        $this->macPic = isset($data['macPic']) ? $data['macPic'] : NULL;
        $this->stareRetur = isset($data['stareRetur']) ? $data['stareRetur'] : NULL;
        $this->lastIdxInM = isset($data['lastIdxInM']) ? $data['lastIdxInM'] : NULL;
        $this->lastIdxOutM = isset($data['lastIdxOutM']) ? $data['lastIdxOutM'] : NULL;
        $this->lastIdxBetM = isset($data['lastIdxBetM']) ? $data['lastIdxBetM'] : NULL;
        $this->lastIdxWinM = isset($data['lastIdxWinM']) ? $data['lastIdxWinM'] : NULL;
        $this->lastIdxGamesM = isset($data['lastIdxGamesM']) ? $data['lastIdxGamesM'] : NULL;
        $this->dtLastM = isset($data['dtLastM']) ? $data['dtLastM'] : NULL;
        $this->lastIdxInE = isset($data['lastIdxInE']) ? $data['lastIdxInE'] : NULL;
        $this->lastIdxOutE = isset($data['lastIdxOutE']) ? $data['lastIdxOutE'] : NULL;
        $this->lastIdxBetE = isset($data['lastIdxBetE']) ? $data['lastIdxBetE'] : NULL;
        $this->dtLastE = isset($data['dtLastE']) ? $data['dtLastE'] : NULL;
        $this->ultimaConectare = isset($data['ultimaConectare']) ? $data['ultimaConectare'] : NULL;
        $this->dtIpPic = isset($data['dtIpPic']) ? $data['dtIpPic'] : NULL;
        $this->idxInMRet = isset($data['idxInMRet']) ? $data['idxInMRet'] : NULL;
        $this->idxOutMRet = isset($data['idxOutMRet']) ? $data['idxOutMRet'] : NULL;
        $this->oreDeLaUltimultPachet = isset($data['oreDeLaUltimulPachet']) ? $data['oreDeLaUltimulPachet'] : NULL;
    }


    public function populate($idAparat)
    {
        $query = "SELECT *,time_format(timediff(now(),{$this->getDb()->getDatabase()}.stareaparate.ultimaConectare),'%H') as oreDeLaUltimulPachet FROM {$this->getDb()->getDatabase()}.stareaparate WHERE idAparat = :idAparat";

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(":idAparat", $idAparat, \PDO::PARAM_INT);

        $stmt->execute();
        $info = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $this->exchangeArray($info);

    }
}