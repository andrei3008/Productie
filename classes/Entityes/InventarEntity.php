<?php

class InventarEnity{
    private $idinv;
    private $idlocatie;
    private $idelement;
    private $cantitate;
    private $stare;
    private $observatii;
    private $dtActivare;
    private $dtBlocare;

    /**
     * @return mixed
     */
    public function getCantitate()
    {
        return $this->cantitate;
    }

    /**
     * @param mixed $cantitate
     */
    public function setCantitate($cantitate)
    {
        $this->cantitate = $cantitate;
    }

    /**
     * @return mixed
     */
    public function getDtActivare()
    {
        return $this->dtActivare;
    }

    /**
     * @param mixed $dtActivare
     */
    public function setDtActivare($dtActivare)
    {
        $this->dtActivare = $dtActivare;
    }

    /**
     * @return mixed
     */
    public function getDtBlocare()
    {
        return $this->dtBlocare;
    }

    /**
     * @param mixed $dtBlocare
     */
    public function setDtBlocare($dtBlocare)
    {
        $this->dtBlocare = $dtBlocare;
    }

    /**
     * @return mixed
     */
    public function getIdelement()
    {
        return $this->idelement;
    }

    /**
     * @param mixed $idelement
     */
    public function setIdelement($idelement)
    {
        $this->idelement = $idelement;
    }

    /**
     * @return mixed
     */
    public function getIdinv()
    {
        return $this->idinv;
    }

    /**
     * @param mixed $idinv
     */
    public function setIdinv($idinv)
    {
        $this->idinv = $idinv;
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
    public function getObservatii()
    {
        return $this->observatii;
    }

    /**
     * @param mixed $observatii
     */
    public function setObservatii($observatii)
    {
        $this->observatii = $observatii;
    }

    /**
     * @return mixed
     */
    public function getStare()
    {
        return $this->stare;
    }

    /**
     * @param mixed $stare
     */
    public function setStare($stare)
    {
        $this->stare = $stare;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idinv        = isset($data['idinv']) ? $data['idinv'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->idelement    = isset($data['idelement']) ? $data['idelement'] : NULL;
        $this->cantitate    = isset($data['cantitate']) ? $data['cantitate'] : NULL;
        $this->stare        = isset($data['stare']) ? $data['stare'] : NULL;
        $this->observatii   = isset($data['observatii']) ? $data['observatii'] : NULL;
        $this->dtActivare   = isset($data['dtActivare']) ? $data['dtActivare'] : NULL;
        $this->dtBlocare    = isset($data['dtBlocare']) ? $data['dtBlocare'] : NULL;
     }
}