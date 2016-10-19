<?php

class SlidedecembrieEntity{
    private $idSil;
    private $idLocatie;
    private $suma;
    private $dt;

    /**
     * @return mixed
     */
    public function getDt()
    {
        return $this->dt;
    }

    /**
     * @param mixed $dt
     */
    public function setDt($dt)
    {
        $this->dt = $dt;
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
    public function getIdSil()
    {
        return $this->idSil;
    }

    /**
     * @param mixed $idSil
     */
    public function setIdSil($idSil)
    {
        $this->idSil = $idSil;
    }

    /**
     * @return mixed
     */
    public function getSuma()
    {
        return $this->suma;
    }

    /**
     * @param mixed $suma
     */
    public function setSuma($suma)
    {
        $this->suma = $suma;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idSil        = isset($data['idSil']) ? $data['idSil'] : NULL;
        $this->idLocatie    = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->suma         = isset($data['suma']) ? $data['suma'] : NULL;
        $this->dt           = isset($data['dt']) ? $data['dt'] : NULL;
    }
}