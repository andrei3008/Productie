<?php

class JocEntity{
    private $idjoc;
    private $idjucator;
    private $idaparat;
    private $idsala;
    private $data_incepere;
    private $data_terminare;

    /**
     * @return mixed
     */
    public function getDataIncepere()
    {
        return $this->data_incepere;
    }

    /**
     * @param mixed $data_incepere
     */
    public function setDataIncepere($data_incepere)
    {
        $this->data_incepere = $data_incepere;
    }

    /**
     * @return mixed
     */
    public function getDataTerminare()
    {
        return $this->data_terminare;
    }

    /**
     * @param mixed $data_terminare
     */
    public function setDataTerminare($data_terminare)
    {
        $this->data_terminare = $data_terminare;
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
    public function getIdjoc()
    {
        return $this->idjoc;
    }

    /**
     * @param mixed $idjoc
     */
    public function setIdjoc($idjoc)
    {
        $this->idjoc = $idjoc;
    }

    /**
     * @return mixed
     */
    public function getIdjucator()
    {
        return $this->idjucator;
    }

    /**
     * @param mixed $idjucator
     */
    public function setIdjucator($idjucator)
    {
        $this->idjucator = $idjucator;
    }

    /**
     * @return mixed
     */
    public function getIdsala()
    {
        return $this->idsala;
    }

    /**
     * @param mixed $idsala
     */
    public function setIdsala($idsala)
    {
        $this->idsala = $idsala;
    }


    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idjoc            = isset($data['idjoc']) ? $data['idjoc'] : NULL;
        $this->idjucator        = isset($data['idjucator']) ? $data['idjucator'] : NULL;
        $this->idaparat         = isset($data['idaparat']) ? $data['idaparat'] : NULL;
        $this->idsala           = isset($data['idsala']) ? $data['idsala'] : NULL;
        $this->data_incepere    = isset($data['data_incepere']) ? $data['data_incepere'] : NULL;
        $this->data_terminare   = isset($data['data_terminare']) ? $data['data_terminare'] : NULL;
    }
}