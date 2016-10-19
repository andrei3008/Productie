<?php
class ElementInventarEntity{
    private $idelement;
    private $denumire;
    private $cantitate;

    public function __construct()
    {
        /**
         * De implementat la nevoie
         */
    }

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
    public function getDenumire()
    {
        return $this->denumire;
    }

    /**
     * @param mixed $denumire
     */
    public function setDenumire($denumire)
    {
        $this->denumire = $denumire;
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

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idelement    = isset($data['idelement']) ? $data['idelement'] : NULL;
        $this->denumire     = isset($data['denumire']) ? $data['denumire'] : NULL;
        $this->cantitate    = isset($data['cantitate']) ? $data['cantitate'] : NULL;
    }
}