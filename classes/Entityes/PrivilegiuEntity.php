<?php

class PrivilegiuEntity{
    private $idpriv;
    private $denumire;

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
    public function getIdpriv()
    {
        return $this->idpriv;
    }

    /**
     * @param mixed $idpriv
     */
    public function setIdpriv($idpriv)
    {
        $this->idpriv = $idpriv;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idpriv   = isset($data['idpriv']) ? $data['idpriv'] : NULL;
        $this->denumire = isset($data['denumire']) ? $data['denumire'] : NULL;
    }
}