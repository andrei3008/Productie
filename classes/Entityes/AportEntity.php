<?php

class AportClass{
    private $idaport;
    private $idlocatie;
    private $nr;
    private $suma;
    private $persoana;
    private $functia;
    private $scopul;
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
    public function getFunctia()
    {
        return $this->functia;
    }

    /**
     * @param mixed $functia
     */
    public function setFunctia($functia)
    {
        $this->functia = $functia;
    }

    /**
     * @return mixed
     */
    public function getIdaport()
    {
        return $this->idaport;
    }

    /**
     * @param mixed $idaport
     */
    public function setIdaport($idaport)
    {
        $this->idaport = $idaport;
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
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * @param mixed $nr
     */
    public function setNr($nr)
    {
        $this->nr = $nr;
    }

    /**
     * @return mixed
     */
    public function getPersoana()
    {
        return $this->persoana;
    }

    /**
     * @param mixed $persoana
     */
    public function setPersoana($persoana)
    {
        $this->persoana = $persoana;
    }

    /**
     * @return mixed
     */
    public function getScopul()
    {
        return $this->scopul;
    }

    /**
     * @param mixed $scopul
     */
    public function setScopul($scopul)
    {
        $this->scopul = $scopul;
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

    /**
     * Se intantiaza obiectul dintr-un vector
     * @param $data
     */
    public function arrayExchange($data){
        $this->idaport      = isset($data['idAport']) ? $data['idAport'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->nr           = isset($data['nr']) ? $data['nr'] : NULL;
        $this->suma         = isset($data['suma']) ? $data['suma'] : NULL;
        $this->persoana     = isset($data['persoana']) ? $data['persoana'] : NULL;
        $this->functia      = isset($data['functia']) ? $data['functia'] : NULL;
        $this->scopul       = isset($data['scopul']) ? $data['scopul'] : NULL;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }
}