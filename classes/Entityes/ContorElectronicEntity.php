<?php

class ContorElectronic{
    private $idel;
    private $idAparat;
    private $idLocatie;
    private $idxInE;
    private $idxOutE;
    private $idxBetE;
    private $creditE;
    private $betE;
    private $numarJocE;
    private $powerUpE;
    private $dtPic;
    private $dtServer;

    public function __construct()
    {
        /**
         * De implementat la nevoie
         */
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
    public function getIdel()
    {
        return $this->idel;
    }

    /**
     * @param mixed $idel
     */
    public function setIdel($idel)
    {
        $this->idel = $idel;
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
    public function getIdxBetE()
    {
        return $this->idxBetE;
    }

    /**
     * @param mixed $idxBetE
     */
    public function setIdxBetE($idxBetE)
    {
        $this->idxBetE = $idxBetE;
    }

    /**
     * @return mixed
     */
    public function getIdxInE()
    {
        return $this->idxInE;
    }

    /**
     * @param mixed $idxInE
     */
    public function setIdxInE($idxInE)
    {
        $this->idxInE = $idxInE;
    }

    /**
     * @return mixed
     */
    public function getIdxOutE()
    {
        return $this->idxOutE;
    }

    /**
     * @param mixed $idxOutE
     */
    public function setIdxOutE($idxOutE)
    {
        $this->idxOutE = $idxOutE;
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

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idel         = isset($data['idel']) ? $data['idel'] : NULL;
        $this->idAparat     = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->idLocatie    = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->idxInE       = isset($data['idxInE']) ? $data['idxInE']  : NULL;
        $this->idxOutE      = isset($data['idxOutE']) ? $data['idxOutE'] : NULL;
        $this->idxBetE      = isset($data['idxBetE']) ? $data['idxBetE'] : NULL;
        $this->creditE      = isset($data['creditE']) ? $data['creditE'] : NULL;
        $this->betE         = isset($data['betE']) ? $data['betE'] : NULL;
        $this->numarJocE    = isset($data['numarJocE']) ? $data['numarJocE'] : NULL;
        $this->powerUpE     = isset($data['powerUpE']) ? $data['powerUpE'] : NULL;
        $this->dtPic        = isset($data['dtPic']) ? $data['dtPic'] : NULL;
        $this->dtServer     = isset($data['dtServer']) ? $data['dtServer'] : NULL;
    }
}