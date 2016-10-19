<?php

class JucatorEntity{
    private $idjucator;
    private $nume;
    private $prenume;
    private $cnp;

    /**
     * @return mixed
     */
    public function getCnp()
    {
        return $this->cnp;
    }

    /**
     * @param mixed $cnp
     */
    public function setCnp($cnp)
    {
        $this->cnp = $cnp;
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
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * @param mixed $nume
     */
    public function setNume($nume)
    {
        $this->nume = $nume;
    }

    /**
     * @return mixed
     */
    public function getPrenume()
    {
        return $this->prenume;
    }

    /**
     * @param mixed $prenume
     */
    public function setPrenume($prenume)
    {
        $this->prenume = $prenume;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idjucator    = isset($data['idjucator']) ? $data['idjucator'] : NULL;
        $this->nume         = isset($data['nume']) ? $data['nume'] : NULL;
        $this->prenume      = isset($data['prenume']) ? $data['prenume'] : NULL;
        $this->cnp          = isset($data['cnp']) ? $data['cnp'] : NULL;
    }
}