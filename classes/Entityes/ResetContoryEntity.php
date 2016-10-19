<?php

class ResetContoriEntity{
    private $idresetce;
    private $idaparat;
    private $idlocatie;
    private $idxInE;
    private $idxOutE;
    private $idxBetE;
    private $idxInM;
    private $idxOutM;
    private $idxBetM;
    private $dtInitiere;
    private $dtTerminare;

    /**
     * @return mixed
     */
    public function getDtInitiere()
    {
        return $this->dtInitiere;
    }

    /**
     * @param mixed $dtInitiere
     */
    public function setDtInitiere($dtInitiere)
    {
        $this->dtInitiere = $dtInitiere;
    }

    /**
     * @return mixed
     */
    public function getDtTerminare()
    {
        return $this->dtTerminare;
    }

    /**
     * @param mixed $dtTerminare
     */
    public function setDtTerminare($dtTerminare)
    {
        $this->dtTerminare = $dtTerminare;
    }

    /**
     * @return mixed
     */
    public function getIdaparat()
    {
        return $this->idaparat;
    }

    /**
     * @param mixed $idaparat
     */
    public function setIdaparat($idaparat)
    {
        $this->idaparat = $idaparat;
    }

    /**
     * @return mixed
     */
    public function getIdlocatie()
    {
        return $this->idlocatie;
    }

    /**
     * @param mixed $idlocatie
     */
    public function setIdlocatie($idlocatie)
    {
        $this->idlocatie = $idlocatie;
    }

    /**
     * @return mixed
     */
    public function getIdresetce()
    {
        return $this->idresetce;
    }

    /**
     * @param mixed $idresetce
     */
    public function setIdresetce($idresetce)
    {
        $this->idresetce = $idresetce;
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

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idresetce    = isset($data['idresetce']) ? $data['idresetce'] : NULL;
        $this->idAparat     = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->idxInE       = isset($data['idxInE']) ? $data['idxInE'] : NULL;
        $this->idxOutE      = isset($data['idxOutE']) ? $data['idxOutE'] : NULL;
        $this->idxBetE      = isset($data['idxBetE']) ? $data['idxBetE'] : NULL;
        $this->idxInM       = isset($data['idxInM']) ? $data['idxInM'] : NULL;
        $this->idxOutM      = isset($data['idxOutM']) ? $data['idxOutM'] : NULL;
        $this->idxBetM      = isset($data['idxBetM']) ? $data['idxBetM'] : NULL;
        $this->dtInitiere   = isset($data['dtInitiere']) ? $data['dtInitiere'] : NULL;
        $this->dtTerminare  = isset($data['dtTerminare']) ? $data['dtTerminare'] : NULL;
    }
}

