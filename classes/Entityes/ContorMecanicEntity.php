<?php

class ContorMecanicEntity
{
    private $idmec;
    private $idAparat;
    private $idLocatie;
    private $idxInM;
    private $idxOutM;
    private $idxBetM;
    private $idxWinM;
    private $idxGamesM;
    private $cashIn;
    private $cashOut;
    private $castig;
    private $setatDe;
    private $nrPac3g;
    private $nrPacWan;
    private $dtPic;
    private $dtServer;
    private $dtOprire;
    private $dtPornire;
    /** @var  DataConnection $db */
    private $db;

    /** @var  SessionClass */
    private $appSettings;

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

    public function __construct(DataConnection $db, SessionClass $sessionClass)
    {
        $this->setDb($db);
        $this->setAppSettings($sessionClass);
    }

    /**
     * @return mixed
     */
    public function getDtServer()
    {
        return $this->dtServer;
    }

    /**
     * @param mixed $dtServer
     */
    public function setDtServer($dtServer)
    {
        $this->dtServer = $dtServer;
    }

    /**
     * @return mixed
     */
    public function getCashIn()
    {
        return $this->cashIn;
    }

    /**
     * @param mixed $cashIn
     */
    public function setCashIn($cashIn)
    {
        $this->cashIn = $cashIn;
    }

    /**
     * @return mixed
     */
    public function getCashOut()
    {
        return $this->cashOut;
    }

    /**
     * @param mixed $cashOut
     */
    public function setCashOut($cashOut)
    {
        $this->cashOut = $cashOut;
    }

    /**
     * @return mixed
     */
    public function getCastig()
    {
        return $this->castig;
    }

    /**
     * @param mixed $castig
     */
    public function setCastig($castig)
    {
        $this->castig = $castig;
    }

    /**
     * @return mixed
     */
    public function getDtOprire()
    {
        return $this->dtOprire;
    }

    /**
     * @param mixed $dtOprire
     */
    public function setDtOprire($dtOprire)
    {
        $this->dtOprire = $dtOprire;
    }

    /**
     * @return mixed
     */
    public function getDtPic()
    {
        return $this->dtPic;
    }

    /**
     * @param mixed $dtPic
     */
    public function setDtPic($dtPic)
    {
        $this->dtPic = $dtPic;
    }

    /**
     * @return mixed
     */
    public function getDtPornire()
    {
        return $this->dtPornire;
    }

    /**
     * @param mixed $dtPornire
     */
    public function setDtPornire($dtPornire)
    {
        $this->dtPornire = $dtPornire;
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
    public function getIdLocatie()
    {
        return $this->idLocatie;
    }

    /**
     * @param mixed $idLocatie
     */
    public function setIdLocatie($idLocatie)
    {
        $this->idLocatie = $idLocatie;
    }

    /**
     * @return mixed
     */
    public function getIdmec()
    {
        return $this->idmec;
    }

    /**
     * @param mixed $idmec
     */
    public function setIdmec($idmec)
    {
        $this->idmec = $idmec;
    }

    /**
     * @return mixed
     */
    public function getIdxBetM()
    {
        return $this->idxBetM;
    }

    /**
     * @param mixed $idxBetM
     */
    public function setIdxBetM($idxBetM)
    {
        $this->idxBetM = $idxBetM;
    }

    /**
     * @return mixed
     */
    public function getIdxGamesM()
    {
        return $this->idxGamesM;
    }

    /**
     * @param mixed $idxGamesM
     */
    public function setIdxGamesM($idxGamesM)
    {
        $this->idxGamesM = $idxGamesM;
    }

    /**
     * @return mixed
     */
    public function getIdxInM()
    {
        return $this->idxInM;
    }

    /**
     * @param mixed $idxInM
     */
    public function setIdxInM($idxInM)
    {
        $this->idxInM = $idxInM;
    }

    /**
     * @return mixed
     */
    public function getIdxOutM()
    {
        return $this->idxOutM;
    }

    /**
     * @param mixed $idxOutM
     */
    public function setIdxOutM($idxOutM)
    {
        $this->idxOutM = $idxOutM;
    }

    /**
     * @return mixed
     */
    public function getIdxWinM()
    {
        return $this->idxWinM;
    }

    /**
     * @param mixed $idxWinM
     */
    public function setIdxWinM($idxWinM)
    {
        $this->idxWinM = $idxWinM;
    }

    /**
     * @return mixed
     */
    public function getNrPac3g()
    {
        return $this->nrPac3g;
    }

    /**
     * @param mixed $nrPac3g
     */
    public function setNrPac3g($nrPac3g)
    {
        $this->nrPac3g = $nrPac3g;
    }

    /**
     * @return mixed
     */
    public function getNrPacWan()
    {
        return $this->nrPacWan;
    }

    /**
     * @param mixed $nrPacWan
     */
    public function setNrPacWan($nrPacWan)
    {
        $this->nrPacWan = $nrPacWan;
    }

    /**
     * @return mixed
     */
    public function getSetatDe()
    {
        return $this->setatDe;
    }

    /**
     * @param mixed $setatDe
     */
    public function setSetatDe($setatDe)
    {
        $this->setatDe = $setatDe;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getRoundCastig()
    {
        return round($this->getCastig() / 100);
    }

    public function getNewIdMec()
    {
        $query = "SELECT contormecanic.idmec FROM contormecanic ORDER BY contormecanic.idmec DESC LIMIT 1";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            $ultimulContor = $stmt->fetch(PDO::FETCH_ASSOC);
            return $ultimulContor['idmec'];
        }
        return FALSE;
    }

    public function exchangeArray($data)
    {
        $this->idmec = isset($data['idmec']) ? $data['idmec'] : NULL;
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->idLocatie = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->idxInM = isset($data['idxInM']) ? $data['idxInM'] : '000000';
        $this->idxOutM = isset($data['idxOutM']) ? $data['idxOutM'] : '000000';
        $this->idxBetM = isset($data['idxBetM']) ? $data['idxBetM'] : 0;
        $this->idxWinM = isset($data['idxWinM']) ? $data['idxWinM'] : 0;
        $this->idxGamesM = isset($data['idxGamesM']) ? $data['idxGamesM'] : 0;
        $this->cashIn = isset($data['cashIn']) ? $data['cashIn'] : 0;
        $this->cashOut = isset($data['cashOut']) ? $data['cashOut'] : 0;
        $this->castig = isset($data['castig']) ? $data['castig'] : 0;
        $this->setatDe = isset($data['setatDe']) ? $data['setatDe'] : 'program';
        $this->nrPac3g = isset($data['nrPac3g']) ? $data['nrPac3g'] : 0;
        $this->nrPacWan = isset($data['nrPacWan']) ? $data['nrPacWan'] : 0;
        $this->dtPic = isset($data['dtPic']) ? $data['dtPic'] : '0';
        $this->dtServer = isset($data['dtServer']) ? $data['dtServer'] : '1000-01-01 00:00:00';
        $this->dtOprire = isset($data['dtOprire']) ? $data['dtOprire'] : '1000-01-01 00:00:00';
        $this->dtPornire = isset($data['dtPornire']) ? $data['dtPornire'] : '1000-01-01 00:00:00';
    }

    public function getRow($id, $an = '', $luna = '')
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.contormecanic{$an}{$luna} WHERE idmec = {$id}";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->exchangeArray($row);
        }
    }

    public function save($an = '', $luna = '')
    {
        $query = "UPDATE {$this->getDb()->getDatabase()}.contormecanic{$an}{$luna} SET
        idAparat =  '{$this->getIdAparat()}',
        idLocatie = '{$this->getIdLocatie()}',
        idxInM = '{$this->getIdxInM()}',
        idxOutM = '{$this->getIdxOutM()}',
        idxBetM = '{$this->getIdxBetM()}',
        idxWinM = '{$this->getIdxWinM()}',
        idxGamesM = '{$this->getIdxGamesM()}',
        cashIn = '{$this->getCashIn()}',
        cashOut = '{$this->getCashOut()}',
        castig = '{$this->getCastig()}',
        setatDe = '{$this->getSetatDe()}',
        nrPac3g = '{$this->getNrPac3g()}',
        nrPacWan = '{$this->getNrPacWan()}',
        dtPic = '{$this->getDtPic()}',
        dtServer = '{$this->getDtServer()}',
        dtOprire = '{$this->getDtOprire()}',
        dtPornire = '{$this->getDtPornire()}'
        WHERE idmec = {$this->getIdmec()};
        ";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute())
            return TRUE;
        return FALSE;
    }


    public function insert($an, $luna)
    {
        $query = "INSERT INTO {$this->getDb()->getDatabase()}.contormecanic{$an}{$luna}
        (idAparat,idLocatie,idxInM,idxOutM,idxBetM,idxWinM,idxGamesM,cashIn,cashOut,castig,setatDe,nrPac3g,nrPacWan,dtPic,dtServer,dtOprire,dtPornire)
        VALUES (
        '{$this->idAparat}','{$this->idLocatie}','{$this->idxInM}','{$this->idxOutM}','{$this->idxBetM}','{$this->idxWinM}','{$this->idxGamesM}',
        '{$this->cashIn}','{$this->cashOut}','{$this->castig}','{$this->setatDe}','{$this->nrPac3g}','{$this->nrPacWan}','{$this->dtPic}','{$this->dtServer}',
        '{$this->dtOprire}','{$this->dtPornire}'
        )";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            echo "Inserted with id : " . $this->getDb()->lastInsertId() . " pentru aparatul cu id {$this->idAparat}</br>";
            return $this->getDb()->lastInsertId();
        }

        return FALSE;
    }

}