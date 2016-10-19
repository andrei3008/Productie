<?php

class ErrorPkEntity{
    /**
     * @var
     */
    private $idPachet;
    /**
     * @var
     */
    private $dataPic;
    /**
     * @var
     */
    private $mac;
    /**
     * @var
     */
    private $idAparat;
    /**
     * @var
     */
    private $versiuneFW;
    /**
     * @var
     */
    private $stareAparat;
    /**
     * @var
     */
    private $indexInM;
    /**
     * @var
     */
    private $indexOutM;
    /**
     * @var
     */
    private $indexBetM;
    /**
     * @var
     */
    private $tensiune12V;
    /**
     * @var
     */
    private $tensiune5V;
    /**
     * @var
     */
    private $tensiune3V3V;
    /**
     * @var
     */
    private $tensiuneBat;
    /**
     * @var
     */
    private $indexInE;
    /**
     * @var
     */
    private $indexOutE;
    /**
     * @var
     */
    private $indexBetE;
    /**
     * @var
     */
    private $creditE;
    /**
     * @var
     */
    private $betE;
    /**
     * @var
     */
    private $numarJocE;
    /**
     * @var
     */
    private $powerUpE;
    /**
     * @var
     */
    private $ip;
    /**
     * @var
     */
    private $dataServer;
    /**
     * @var
     */
    private $exceptia;

    public function __construct(array $data){
        $this->exchangeArray($data);
    }

    /**
     * @return mixed
     */
    public function getBetE()
    {
        return $this->betE;
    }

    /**
     * @param mixed $betE
     */
    public function setBetE($betE)
    {
        $this->betE = $betE;
    }

    /**
     * @return mixed
     */
    public function getCreditE()
    {
        return $this->creditE;
    }

    /**
     * @param mixed $creditE
     */
    public function setCreditE($creditE)
    {
        $this->creditE = $creditE;
    }

    /**
     * @return mixed
     */
    public function getDataPic()
    {
        return $this->dataPic;
    }

    /**
     * @param mixed $dataPic
     */
    public function setDataPic($dataPic)
    {
        $this->dataPic = $dataPic;
    }

    /**
     * @return mixed
     */
    public function getDataServer()
    {
        $data = strtotime($this->dataServer);
        return date('d-M-Y',$data);
    }

    /**
     * @param mixed $dataServer
     */
    public function setDataServer($dataServer)
    {
        $this->dataServer = $dataServer;
    }

    /**
     * @return mixed
     */
    public function getExceptia()
    {
        return $this->exceptia;
    }

    /**
     * @param mixed $exceptia
     */
    public function setExceptia($exceptia)
    {
        $this->exceptia = $exceptia;
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
    public function getIdPachet()
    {
        return $this->idPachet;
    }

    /**
     * @param mixed $idPachet
     */
    public function setIdPachet($idPachet)
    {
        $this->idPachet = $idPachet;
    }

    /**
     * @return mixed
     */
    public function getIndexBetE()
    {
        return $this->indexBetE;
    }

    /**
     * @param mixed $indexBetE
     */
    public function setIndexBetE($indexBetE)
    {
        $this->indexBetE = $indexBetE;
    }

    /**
     * @return mixed
     */
    public function getIndexBetM()
    {
        return $this->indexBetM;
    }

    /**
     * @param mixed $indexBetM
     */
    public function setIndexBetM($indexBetM)
    {
        $this->indexBetM = $indexBetM;
    }

    /**
     * @return mixed
     */
    public function getIndexInE()
    {
        return $this->indexInE;
    }

    /**
     * @param mixed $indexInE
     */
    public function setIndexInE($indexInE)
    {
        $this->indexInE = $indexInE;
    }

    /**
     * @return mixed
     */
    public function getIndexInM()
    {
        return $this->indexInM;
    }

    /**
     * @param mixed $indexInM
     */
    public function setIndexInM($indexInM)
    {
        $this->indexInM = $indexInM;
    }

    /**
     * @return mixed
     */
    public function getIndexOutE()
    {
        return $this->indexOutE;
    }

    /**
     * @param mixed $indexOutE
     */
    public function setIndexOutE($indexOutE)
    {
        $this->indexOutE = $indexOutE;
    }

    /**
     * @return mixed
     */
    public function getIndexOutM()
    {
        return $this->indexOutM;
    }

    /**
     * @param mixed $indexOutM
     */
    public function setIndexOutM($indexOutM)
    {
        $this->indexOutM = $indexOutM;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return mixed
     */
    public function getNumarJocE()
    {
        return $this->numarJocE;
    }

    /**
     * @param mixed $numarJocE
     */
    public function setNumarJocE($numarJocE)
    {
        $this->numarJocE = $numarJocE;
    }

    /**
     * @return mixed
     */
    public function getPowerUpE()
    {
        return $this->powerUpE;
    }

    /**
     * @param mixed $powerUpE
     */
    public function setPowerUpE($powerUpE)
    {
        $this->powerUpE = $powerUpE;
    }

    /**
     * @return mixed
     */
    public function getStareAparat()
    {
        return $this->stareAparat;
    }

    /**
     * @param mixed $stareAparat
     */
    public function setStareAparat($stareAparat)
    {
        $this->stareAparat = $stareAparat;
    }

    /**
     * @return mixed
     */
    public function getTensiune12V()
    {
        return $this->tensiune12V;
    }

    /**
     * @param mixed $tensiune12V
     */
    public function setTensiune12V($tensiune12V)
    {
        $this->tensiune12V = $tensiune12V;
    }

    /**
     * @return mixed
     */
    public function getTensiune3V3V()
    {
        return $this->tensiune3V3V;
    }

    /**
     * @param mixed $tensiune3V3V
     */
    public function setTensiune3V3V($tensiune3V3V)
    {
        $this->tensiune3V3V = $tensiune3V3V;
    }

    /**
     * @return mixed
     */
    public function getTensiune5V()
    {
        return $this->tensiune5V;
    }

    /**
     * @param mixed $tensiune5V
     */
    public function setTensiune5V($tensiune5V)
    {
        $this->tensiune5V = $tensiune5V;
    }

    /**
     * @return mixed
     */
    public function getTensiuneBat()
    {
        return $this->tensiuneBat;
    }

    /**
     * @param mixed $tensiuneBat
     */
    public function setTensiuneBat($tensiuneBat)
    {
        $this->tensiuneBat = $tensiuneBat;
    }

    /**
     * @return mixed
     */
    public function getVersiuneFW()
    {
        return $this->versiuneFW;
    }

    /**
     * @param mixed $versiuneFW
     */
    public function setVersiuneFW($versiuneFW)
    {
        $this->versiuneFW = $versiuneFW;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }


    public function exchangeArray($data){
        $this->idPachet     = isset($data['idPachet']) ? $data['idPachet'] : NULL;
        $this->dataPic      = isset($data['dataPic']) ? $data['dataPic'] : NULL;
        $this->mac          = isset($data['mac']) ? $data['mac'] : NULL;
        $this->idAparat     = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->versiuneFW   = isset($data['versiuneFW']) ? $data['versiuneFW'] : NULL;
        $this->stareAparat  = isset($data['stareAparat']) ? $data['stareAparat'] : NULL;
        $this->indexInM     = isset($data['indexInM']) ? $data['indexInM'] : NULL;
        $this->indexOutM    = isset($data['indexOutM']) ? $data['indexOutM'] : NULL;
        $this->indexBetM    = isset($data['indexBetM']) ? $data['indexBetM'] : NULL;
        $this->tensiune12V  = isset($data['tensiune12V']) ? $data['tensiune12V'] : NULL;
        $this->tensiune5V   = isset($data['tensiune5V']) ? $data['tensiune5V'] : NULL;
        $this->tensiune3V3V = isset($data['tensiune3V3V']) ? $data['tensiune3V3V'] : NULL;
        $this->tensiuneBat  = isset($data['tensiuneBat']) ? $data['tensiuneBat'] : NULL;
        $this->indexInE     = isset($data['indexInE']) ? $data['indexInE'] : NULL;
        $this->indexOutE    = isset($data['indexOutE']) ? $data['indexOutE'] : NULL;
        $this->indexBetE    = isset($data['indexBetE']) ? $data['indexBetE'] : NULL;
        $this->creditE      = isset($data['creditE']) ? $data['creditE'] : NULL;
        $this->betE         = isset($data['betE']) ? $data['betE'] : NULL;
        $this->numarJocE    = isset($data['numarJocE']) ? $data['numarJocE'] : NULL;
        $this->powerUpE     = isset($data['powerUpE']) ? $data['powerUpE'] : NULL;
        $this->ip           = isset($data['ip']) ? $data['ip'] : NULL;
        $this->dataServer   = isset($data['dataServer']) ? $data['dataServer'] : NULL;
        $this->exceptia     = isset($data['exceptia']) ? $data['exceptia'] : NULL;
    }

    public function getProblem(){
        $exceptia = $this->getExceptia();
        if($this->isInString($exceptia,"IN") !== FALSE){
            return 'IN';
        }
        if($this->isInString($exceptia,"OUT") !== FALSE){
            return 'OUT';
        }

        return "TIP DE EROARE NEDEFINIT";
    }

    public function isInString($value,$searched){
        if(strpos($value,$searched)!== FALSE){
            return TRUE;
        }

        return FALSE;
    }
}