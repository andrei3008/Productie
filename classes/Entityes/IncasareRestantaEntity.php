<?php

class IncasareRestantaEntity{
    private $idincasare;
    private $idlocatie;
    private $suma;
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
    public function getIdincasare()
    {
        return $this->idincasare;
    }

    /**
     * @param mixed $idincasare
     */
    public function setIdincasare($idincasare)
    {
        $this->idincasare = $idincasare;
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
        $this->idincasare   = isset($data['idincasare']) ? $data['idincasare'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->suma         = isset($data['suma']) ? $data['suma'] : NULL;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }
}