<?php

class InchidereEntity{
    private $idinchideri;
    private $idlocatie;
    private $soldluna;
    private $report;
    private $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getIdinchideri()
    {
        return $this->idinchideri;
    }

    /**
     * @param mixed $idinchideri
     */
    public function setIdinchideri($idinchideri)
    {
        $this->idinchideri = $idinchideri;
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
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     */
    public function setReport($report)
    {
        $this->report = $report;
    }

    /**
     * @return mixed
     */
    public function getSoldluna()
    {
        return $this->soldluna;
    }

    /**
     * @param mixed $soldluna
     */
    public function setSoldluna($soldluna)
    {
        $this->soldluna = $soldluna;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray(){
        $this->idinchideri  = isset($data['idinchideri']) ? $data['idinchideri'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->soldluna     = isset($data['soldluna']) ? $data['report'] : NULL;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }
}