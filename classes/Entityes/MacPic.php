<?php

class MacPic
{
    /** @var  DataConnection */
    protected $db;
    /** @var SessionClass */
    protected $appSettings;
    /** @var int */
    private $idmacpic;
    /** @var  string */
    private $macPic;
    /** @var string */
    private $dataInserare;
    /** @var  int */
    public $idxInM;
    /** @var  int */
    public $idxOutM;
    /** @var  int */
    public $soft;
    /** @var  String */
    public $ip;
    /** @var  String */
    public $dataPrimaInserare;
    /** @var  String */
    public $dataUltimaInserare;



    public function __construct(DataConnection $db,SessionClass $appSettings)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettings);
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
     * @return int
     */
    public function getIdmacpic()
    {
        return $this->idmacpic;
    }

    /**
     * @param int $idmacpic
     */
    public function setIdmacpic($idmacpic)
    {
        $this->idmacpic = $idmacpic;
    }

    /**
     * @return string
     */
    public function getMacPic()
    {
        return $this->macPic;
    }

    /**
     * @param string $macPic
     */
    public function setMacPic($macPic)
    {
        $this->macPic = $macPic;
    }

    /**
     * @return string
     */
    public function getDataInserare()
    {
        return $this->dataInserare;
    }

    /**
     * @return int
     */
    public function getIdxInM()
    {
        return $this->idxInM;
    }

    /**
     * @param int $idxInM
     */
    public function setIdxInM($idxInM)
    {
        $this->idxInM = $idxInM;
    }

    /**
     * @return int
     */
    public function getIdxOutM()
    {
        return $this->idxOutM;
    }

    /**
     * @param int $idxOutM
     */
    public function setIdxOutM($idxOutM)
    {
        $this->idxOutM = $idxOutM;
    }

    /**
     * @return int
     */
    public function getSoft()
    {
        return $this->soft;
    }

    /**
     * @param int $soft
     */
    public function setSoft($soft)
    {
        $this->soft = $soft;
    }

    /**
     * @return String
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param String $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return String
     */
    public function getDataPrimaInserare()
    {
        return $this->dataPrimaInserare;
    }

    /**
     * @param String $dataPrimaInserare
     */
    public function setDataPrimaInserare($dataPrimaInserare)
    {
        $this->dataPrimaInserare = $dataPrimaInserare;
    }

    /**
     * @return String
     */
    public function getDataUltimaInserare()
    {
        return $this->dataUltimaInserare;
    }

    /**
     * @param String $dataUltimaInserare
     */
    public function setDataUltimaInserare($dataUltimaInserare)
    {
        $this->dataUltimaInserare = $dataUltimaInserare;
    }

    /**
     * @param string $dataInserare
     */
    public function setDataInserare($dataInserare)
    {
        $this->dataInserare = $dataInserare;
    }
    /**
     * ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getIdAparat()
    {
        return $this->idAparat;
    }
    /**
     * ADDED - 13.10.2016 - SILVIU
     * @return String
     */
    public function getidAparatMac()
    {
        return $this->idAparatMac;
    }
    /**
     * ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getIdLocatie()
    {
        return $this->idLocatie;
    }
    /**
     *ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getAdresa()
    {
        return $this->adresa;
    }
    /**
     *ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getSeria()
    {
        return $this->seria;
    }
    /**
     *ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getStareRetur()
    {
        return $this->stareRetur;
    }
    /**
     *ADDED - 01.09.2016 - SILVIU
     * @return String
     */
    public function getDenumireLocatie()
    {
        return $this->denumire;
    }
    public function exchangeArray($data)
    {
        $this->idmacpic = isset($data['idmacpic']) ? $data['idmacpic'] : NULL;
        $this->macPic = isset($data['macPic']) ? $data['macPic'] : NULL;
        $this->idxInM = isset($data['idxInM']) ? $data['idxInM'] : NULL;
        $this->idxOutM = isset($data['idxOutM']) ? $data['idxOutM'] : NULL;
        $this->soft = isset($data['soft']) ? $data['soft'] : NULL;
        $this->ip = isset($data['ip']) ? $data['ip'] : NULL;
        $this->dataPrimaInserare = isset($data['dataPrimaInserare'])? $data['dataPrimaInserare'] : NULL;
        $this->dataUltimaInserare = isset($data['dataUltimaInserare']) ? $data['dataUltimaInserare'] : NULL;
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->idAparatMac = isset($data['idAparatMac']) ? $data['idAparatMac'] : NULL;
        $this->idLocatie = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->adresa = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->seria = isset($data['seria']) ? $data['seria'] : NULL;
        $this->stareRetur = isset($data['stareRetur']) ? $data['stareRetur'] : NULL;
        $this->denumire = isset($data['denumire']) ? $data['denumire'] : NULL;
    }

    public function getObjectVars()
    {
        return get_object_vars($this);
    }
}