<?php

class ActeModel{
    private $ID;
    private $NrAct;
    private $Data;
    private $Explicatie;
    private $Incasari;
    private $Plati;
    private $TipInregistrare;

    public function __construct()
    {
        /**
         *
         */
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->Data;
    }

    /**
     * @param mixed $Data
     */
    public function setData($Data)
    {
        $this->Data = $Data;
    }

    /**
     * @return mixed
     */
    public function getExplicatie()
    {
        return $this->Explicatie;
    }

    /**
     * @param mixed $Explicatie
     */
    public function setExplicatie($Explicatie)
    {
        $this->Explicatie = $Explicatie;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getIncasari()
    {
        return $this->Incasari;
    }

    /**
     * @param mixed $Incasari
     */
    public function setIncasari($Incasari)
    {
        $this->Incasari = $Incasari;
    }

    /**
     * @return mixed
     */
    public function getNrAct()
    {
        return $this->NrAct;
    }

    /**
     * @param mixed $NrAct
     */
    public function setNrAct($NrAct)
    {
        $this->NrAct = $NrAct;
    }

    /**
     * @return mixed
     */
    public function getPlati()
    {
        return $this->Plati;
    }

    /**
     * @param mixed $Plati
     */
    public function setPlati($Plati)
    {
        $this->Plati = $Plati;
    }

    /**
     * @return mixed
     */
    public function getTipInregistrare()
    {
        return $this->TipInregistrare;
    }

    /**
     * @param mixed $TipInregistrare
     */
    public function setTipInregistrare($TipInregistrare)
    {
        $this->TipInregistrare = $TipInregistrare;
    }

    /**
     * Instantiere din arrray de rezultat
     */
    public function exchangeArray($data){
        $this->ID               = (isset($data['ID']) ? $data['ID'] : NULL);
        $this->NrAct            = (isset($data['NrAct']) ? $data['NrAct'] : NULL);
        $this->Data             = (isset($data['Data']) ? $data['Data'] : NULL);
        $this->Explicatie       = (isset($data['Explicatie']) ? $data['Explicatie'] : NULL);
        $this->Incasari         = (isset($data['Incasari']) ? $data['Incasari'] : NULL);
        $this->Plati            = (isset($data['Plati']) ? $data['Plati'] : NULL);
        $this->TipInregistrare  = (isset($data['TipInregistrare']) ? $data['TipInregistrare'] : NULL);
    }

    /**
     * Get Array Copy Of Object
     */
    public function getArrayCopy(){
        return get_object_vars($this);
    }

}