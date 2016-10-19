<?php

class RestantaEntity{
    private $idRestanta;
    private $idLocatie;
    private $sumaRestanta;
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
    public function getIdRestanta()
    {
        return $this->idRestanta;
    }

    /**
     * @param mixed $idRestanta
     */
    public function setIdRestanta($idRestanta)
    {
        $this->idRestanta = $idRestanta;
    }

    /**
     * @return mixed
     */
    public function getSumaRestanta()
    {
        return $this->sumaRestanta;
    }

    /**
     * @param mixed $sumaRestanta
     */
    public function setSumaRestanta($sumaRestanta)
    {
        $this->sumaRestanta = $sumaRestanta;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArrayCopy(){
        $this->idRestanta   = isset($data['idRestanta']) ? $data['idRestanta'] : NULL;
        $this->idLocatie    = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->sumaRestanta = isset($data['sumaRestanta']) ? $data['sumaRestanta'] : NULL;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }
}